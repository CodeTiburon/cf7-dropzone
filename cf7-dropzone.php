<?php
/*
 * The plugin bootstrap file
 * Plugin Name: Contact Form 7 DropzoneJs
 * Plugin URI:  http://www.codetiburon.com
 * Description: Integrates DropzoneJs functionality to the popular Contact Form 7 plugin.
 * Author: CodeTiburon
 * Author URI: http://www.codetiburon.com
 * Version: 1.0
*/

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/cf7-dropzone-plugin.php';

СF7_Dropzone_Plugin::run();



