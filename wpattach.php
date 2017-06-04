<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/Gabrielchihonglee
 * @since             1.0.0
 * @package           Wpattach
 *
 * @wordpress-plugin
 * Plugin Name:       WP Attach
 * Plugin URI:        https://github.com/Gabrielchihonglee/wpattach
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Gabriel Chi Hong Lee
 * Author URI:        https://github.com/Gabrielchihonglee
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpattach
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpattach-activator.php
 */
function activate_wpattach() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpattach-activator.php';
	Wpattach_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpattach-deactivator.php
 */
function deactivate_wpattach() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpattach-deactivator.php';
	Wpattach_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpattach' );
register_deactivation_hook( __FILE__, 'deactivate_wpattach' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpattach.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpattach() {

	$plugin = new Wpattach();
	$plugin->run();

}
run_wpattach();

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
     $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 


function GetRemoteLastModified( $uri )
{
    // default
    $unixtime = 0;
    
    $fp = fopen( $uri, "r" );
    if( !$fp ) {return;}
    
    $MetaData = stream_get_meta_data( $fp );
        
    foreach( $MetaData['wrapper_data'] as $response )
    {
        // case: redirection
        if( substr( strtolower($response), 0, 10 ) == 'location: ' )
        {
            $newUri = substr( $response, 10 );
            fclose( $fp );
            return GetRemoteLastModified( $newUri );
        }
        // case: last-modified
        elseif( substr( strtolower($response), 0, 15 ) == 'last-modified: ' )
        {
            $unixtime = strtotime( substr($response, 15) );
            break;
        }
    }
    fclose( $fp );
    return $unixtime;
}


function wpattach_shortcode( $atts ) 
{ $wpattachvar = shortcode_atts( array( 'link' => '(blank)', 'size' => 'medium', ), $atts );
 
 $ch = curl_init($wpattachvar["link"]);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $data = curl_exec($ch);
    curl_close($ch);

    if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {

        // Contains file size in bytes
        $contentLength = (int)$matches[1];

    }
 
                                      return '
    <div class="attachbox" style="max-width:300px;">
        <div class="row" style="display: flex; flex-wrap: wrap; border: 1px solid #ddd; position: relative;">
            <div class="col-xs-3" style="display: flex; flex-direction: column;">
            <a href="'.$wpattachvar["link"].'"><img src="http://www.microprojets.org/wp-content/uploads/pdf.png" style="width:75%; height: auto; max-width:70px; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);"></a>
            </div>
            <div class="col-xs-9" style="display: flex; flex-direction: column;">
                <h5><a href="'.$wpattachvar["link"].'">'.basename($wpattachvar["link"]).PHP_EOL.'</a></h5>
                <p>File size: '.formatBytes($contentLength).'
                    <br>Last updated: '.gmdate("Y-m-d", GetRemoteLastModified($wpattachvar["link"])).'</p>
            </div>
        </div>
    </div>
    ' ; } add_shortcode( 'wpattach', 'wpattach_shortcode' );