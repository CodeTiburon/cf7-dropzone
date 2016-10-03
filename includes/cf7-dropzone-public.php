<?php
use CodeTiburon\Wordbox\View;

class СF7_Dropzone_Public {

    private $_tag = null;
    private $_form = null;

    public function __construct() {

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueueStyles' ], 10);
	add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ], 10);
        add_action( 'wp_footer', [ $this, 'setupJs' ] );

        // cf7 hooks
        add_filter( 'wpcf7_validate_dropzone', [ $this, 'handleUpload' ], 10, 2 );
        add_filter( 'wpcf7_validate_dropzone*', [ $this, 'handleUpload' ], 10, 2 );
    }

    public function shortcodeHandler( $tag ) {

        $tag = new WPCF7_Shortcode( $tag );

        $html = '';

	if ( !empty( $tag->name ) ) {

            $this->_tag = $tag;
            $this->_form = WPCF7_ContactForm::get_current();

            $validationError = wpcf7_get_validation_error( $tag->name );
            $class = wpcf7_form_controls_class( $tag->type );
            if ( $validationError ) {
                    $class .= ' wpcf7-not-valid';
            }

            $vars = [];
            $vars['class'] = $tag->get_class_option( $class );
            $vars['aria-required'] = $tag->is_required()?'true':'false';
            $vars['aria-invalid'] = $validationError ? 'true' : 'false';
            $vars['name'] = $tag->name;
            $vars['form_id'] =$this->_form->id();
            $vars['preview_container_id'] = 'dz-preview-container';

            $vars['preview_template_id'] = 'dz-preview-template';
            $vars['preview_template'] = '';
            $templateFile = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $tag->get_option('preview_template', '', true);
            if (!empty($templateFile) && is_file($templateFile)) {
                $vars['preview_template'] = file_get_contents($templateFile);
            }

            $html = View::make('public/shortcode', $vars, false);
        }

        return $html;
    }


    private function _booleanVal($val) {

        if (is_string($val)) {
            $val = empty($val)?'false':'true';
        } elseif(is_bool($val)) {
            $val = !$val?'false':'true';
        }

        return $val;
    }

    /**
     * Prints javascript to setup Dropzone element
     * @return string
     */
    public function setupJs() {

        if (!$this->_tag instanceof WPCF7_Shortcode) {
            return;
        }

        $vars = ['param_name' => $this->_tag->name,
                 'add_remove_links' => $this->_booleanVal($this->_tag->get_option('add_remove_links', '', true)),
                 'accepted_files' =>  '', //'.doc,.docx,.txt,.rtf,.pdf,.xls,.xlsx,.odt,.ods,.odp',
                 'max_files' => 1,
                 'max_filesize' => $this->_tag->get_option('max_filesize', '', true),
                 'create_image_thumbnails' => $this->_booleanVal($this->_tag->get_option('create_image_thumbnails', '', true)),
                 'form_id' => $this->_form->id(),
                 'name' => $this->_tag->name,
                 'preview_template_id' => 'dz-preview-template'];

        $max_files = $this->_tag->get_option('max_files', 'int', true);
        if ($max_files) {
            $vars['max_files'] = $max_files;
        }

        $accept = $this->_tag->get_option('accepted_files', '', true);
        if ($accept) {
            $vars['accepted_files'] = preg_replace('/\|+/', ',', $accept);
        }
        $message = isset( $this->_tag->values[0] ) ? $this->_tag->values[0] : '';
        $vars['message'] = empty($message)?__('Drag & drop your file or click to browse', 'contact-form-7'):$message;

        return View::make('public/script', $vars);
    }

    /**
     *
     */
    public function handleUpload($result, $tag) {
        $tag = new WPCF7_Shortcode( $tag );

	$name = $tag->name;

        $file = isset( $_FILES[$name] ) ? $_FILES[$name] : null;

	if ( $file['error'] && UPLOAD_ERR_NO_FILE != $file['error'] ) {
            $result->invalidate( $tag, wpcf7_get_message( 'upload_failed_php_error' ) );
            return $result;
	}

	if ( empty( $file['tmp_name'] ) && $tag->is_required() ) {
            $result->invalidate( $tag, wpcf7_get_message( 'invalid_required' ) );
            return $result;
	}

	if ( ! is_uploaded_file( $file['tmp_name'] ) ) {
		return $result;
        }

        wpcf7_init_uploads(); // Confirm upload dir
	$uploads_dir = wpcf7_upload_tmp_dir();
	$uploads_dir = wpcf7_maybe_add_random_dir( $uploads_dir );

	$filename = $file['name'];
	$filename = wpcf7_canonicalize( $filename );
	$filename = sanitize_file_name( $filename );
	$filename = wpcf7_antiscript_file_name( $filename );
	$filename = wp_unique_filename( $uploads_dir, $filename );

	$new_file = trailingslashit( $uploads_dir ) . $filename;

	if ( false === @move_uploaded_file( $file['tmp_name'], $new_file ) ) {
		$result->invalidate( $tag, wpcf7_get_message( 'upload_failed' ) );
		return $result;
	}

	// Make sure the uploaded file is only readable for the owner process
	@chmod( $new_file, 0400 );

	if ( $submission = WPCF7_Submission::get_instance() ) {
		$submission->add_uploaded_file( $name, $new_file );
	}

	return $result;


    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueStyles() {

        wp_enqueue_style( СF7_Dropzone_Plugin::PLUGIN_NAME,  plugin_dir_url(dirname(__FILE__)) . 'public/css/dropzone.css', array(), СF7_Dropzone_Plugin::VERSION, 'all' );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueScripts() {

        wp_enqueue_script( СF7_Dropzone_Plugin::PLUGIN_NAME,  plugin_dir_url(dirname(__FILE__)) . 'public/js/dropzone.js', array(), СF7_Dropzone_Plugin::VERSION, true );
    }
}
