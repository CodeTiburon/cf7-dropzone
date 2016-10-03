<?php
use CodeTiburon\Wordbox\View;

class CF7_Dropzone_Admin {
    
        
    public function __construct() 
    {
        add_action( 'admin_init', array( $this, 'registerTagGenerator' ), 55 );
        
    }

    /**
     * Generates a new tag
     */
    public function registerTagGenerator() 
    {
        if ( function_exists( 'wpcf7_add_tag_generator' ) ) {
            wpcf7_add_tag_generator( СF7_Dropzone_Plugin::TAG_NAME, __( СF7_Dropzone_Plugin::TAG_BUTTON_NAME, 'contact-form-7' ), 'cf7_dropzone_panel', array( $this, 'showTagGenerator' ) );
        }
    }

    /**
     * Shows a settings page on Form Builder plugin of Contact Form 7
     *
     */
    public static function showTagGenerator( $contactForm, $args = '' ) 
    {
        $args = wp_parse_args( $args, array() );

        return View::make('admin/tag_generator', ['args' => $args, 'type' => СF7_Dropzone_Plugin::TAG_NAME]);
    }

}

