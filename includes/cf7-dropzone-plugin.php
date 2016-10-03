<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/View.php';
/**
 * The class responsible for defining all actions that occur in the admin area.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cf7-dropzone-admin.php';

/**
 * The class responsible for defining all actions that occur in the public-facing
 * side of the site.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cf7-dropzone-public.php';

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    СF7_Dropzone
 */
class СF7_Dropzone_Plugin {
    
    const TAG_NAME = 'dropzone';
    const TAG_BUTTON_NAME = 'DropzoneJS';
    const VERSION = '1.0';
    CONST PLUGIN_NAME = 'cf7-dropzone';
    
    /**
      * Static property to hold our singleton instance
      *
      */
    static $plugin = null;
    
    public $admin;
    public $public;
    
    private function __construct() {
        if (is_admin()) {
            $this->admin = new CF7_Dropzone_Admin();
        } else {
            $this->public = new СF7_Dropzone_Public();
        }
        
        add_action( 'wpcf7_init', array( $this, 'addShortcode' ) );
    }

   
    /**
     * Registers New shortcode in Contact Form 7. This is displayed while creating
     * a new form in Contact Form 7
     */
    public function addShortcode() {
        wpcf7_add_shortcode( [СF7_Dropzone_Plugin::TAG_NAME, СF7_Dropzone_Plugin::TAG_NAME.'*'], 
                             [ СF7_Dropzone_Plugin::i()->public, 'shortcodeHandler' ],
                             true );
        
    }

    
    
    public static function run() {
        if ( !self::$plugin ) {
                self::$plugin = new self;
        }
        return self::$plugin;
    }
    
     public static function i() {
        
        return self::run();
    }


}
