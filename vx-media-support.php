<?php
/*
/**
Plugin Name: VX Media - Support & Security
Plugin URI: https://www.vx-media.de
Description: WP durch leistungsfähige und professionelle Codes erweitern.
Version: 1.1.6
Author: VX Media GmbH
Author URI: https://www.vx-media.de
License: GPL12
Icon1x: https://raw.githubusercontent.com/froger-me/wp-plugin-update-server/master/examples/icon-128x128.png
Icon2x: https://raw.githubusercontent.com/froger-me/wp-plugin-update-server/master/examples/icon-256x256.png
BannerHigh: https://raw.githubusercontent.com/froger-me/wp-plugin-update-server/master/examples/banner-1544x500.png
BannerLow: https://vx-media.de/VX-PLUGINS/vx-media-support/image/banner-722x250.png
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function vx_support_style()
{
    wp_enqueue_style('admin-styles-vx-support', plugin_dir_url(__FILE__) . 'css/style.css');
}

add_action('admin_enqueue_scripts', 'vx_support_style');



// VX MEDIA SUPPORT DASHBOARD
add_action('wp_dashboard_setup', 'vx_media_dashboard_widgets');

function vx_media_dashboard_widgets()
{
    wp_add_dashboard_widget('vx_media_help_widget', 'VX Media GmbH | Hilfe & Support', 'vx_media_dashboard_help');
    wp_add_dashboard_widget('vx_media_status_widget', 'VX Media GmbH | Status der Webseite', 'vx_media_dashboard_status');
}


// Hilfe Dashboard
function vx_media_dashboard_help()
{
    $versionPlugin = "1.1.6";
?>

    <div class="vx-support-wrapper">
        <p>Herzlich Willkommen in Ihrem Backend! Sie benötigen Hilfe? <br><br>Sie erreichen uns unter: <br>
            <strong>Mobil:</strong> 07307/3009274<br>
            <strong>Mail:</strong> support@vx-media.de<br><br>

            Ihr Design & Code Team<br>
            <strong>VX Media GmbH</strong>
        </p>

        <span>Version <?php echo $versionPlugin; ?></span>
    </div>

    <?php }



// Status Dashboard
function vx_media_dashboard_status()
{
    //FALSE = LIZENS AKTIV
    $vx_media_status_lizens = true;

    // ########################## Wenn Lizens vorhanden ##########################

    if ($vx_media_status_lizens == false) {

        $checkImage = plugin_dir_url(__FILE__) . 'css/images/check-circle-solid.svg';
        $checkImageThumbUp = plugin_dir_url(__FILE__) . 'css/images/thumbs-up-solid.svg';

    ?>

        <div class="status-headline">
            24/7 Service & Wartung Premium
        </div>

        <div class="status-liste lizens-aktiv">
            <ul>
                <li><img src="<?php echo $checkImage; ?>"> Aktualisierung des WordPress Cores</li>
                <li><img src="<?php echo $checkImage; ?>"> Aktualisierung von Plugins und ihres Themes</li>
                <li><img src="<?php echo $checkImage; ?>"> externes Langzeit-Backup zum Schutz vor Totalausfall</li>
                <li><img src="<?php echo $checkImage; ?>"> Malwarebeseitigung und Spambereinigung</li>
                <li><img src="<?php echo $checkImage; ?>"> Überwachung des Online-Status</li>
                <li><img src="<?php echo $checkImage; ?>"> Reparatur „white screen of death“</li>
                <li><img src="<?php echo $checkImage; ?>"> Problembehebung nach einem WordPress Core Update</li>
                <li><img src="<?php echo $checkImage; ?>"> Verbesserung der Website-Sicherheit</li>
                <li><img src="<?php echo $checkImage; ?>"> Bildoptimierung und Spamprävention</li>
                <li><img src="<?php echo $checkImage; ?>"> Wiederherstellung einer gehackten Website</li>
            </ul>
        </div>

        <div class="notfall-hotline"><span>NOTFALL HOTLINE</span>0162 / 928 5 928</div>

    <?php
        // ########################## Wenn keine Lizens vorhanden ##########################
    } else {

        $exclamationImage = plugin_dir_url(__FILE__) . 'css/images/exclamation-circle-solid.svg';
        $exclamationTriangelImage = plugin_dir_url(__FILE__) . 'css/images/exclamation-triangle-solid.svg';

    ?>

        <div class="status-headline lizens-inaktiv ">
            <img class="pulse" src="<?php echo $exclamationTriangelImage; ?>"> KEIN WARTUNGS VERTRAG <img class="pulse" src="<?php echo $exclamationTriangelImage; ?>">
        </div>

        <div class="status-liste lizens-inaktiv">
            <ul>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Aktualisierung des WordPress Cores</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Aktualisierung von Plugins und ihres Themes</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Kein externes Langzeit-Backup zum Schutz vor Totalausfall</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Malwarebeseitigung und Spambereinigung</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Überwachung des Online-Status</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Reparatur „white screen of death“</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Problembehebung nach WordPress Core Update</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Verbesserung der Website-Sicherheit</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Bildoptimierung und Spamprävention</li>
                <li><img src="<?php echo $exclamationImage; ?>"> Keine Wiederherstellung einer gehackten Website</li>
            </ul>
        </div>


<?php }
}

// Sortierung der Dashboard Felder immer auf die selbe Position
add_action('admin_init', 'set_dashboard_meta_order');
function set_dashboard_meta_order()
{
    $id = get_current_user_id(); //we need to know who we're updating
    $meta_value = array(
        'normal' => 'vx_media_dashboard_widgets', //first key/value pair from the above serialized array
        'side' => 'vx_media_status_widget', //second key/value pair from the above serialized array
        'column3' => '', //third key/value pair from the above serialized array
        'column4' => '', //last key/value pair from the above serialized array
    );
    update_user_meta($id, 'meta-box-order_dashboard', $meta_value); //update the user meta with the user's ID, the meta_key meta-box-order_dashboard, and the new meta_value
}


//* Administrator Footer
add_filter('admin_footer_text', 'vx_support_footer');
function vx_support_footer()
{
    echo '<span id="footer-thankyou">Made with &hearts; by VX Media GmbH</span>';
}



//* Login Logo = Favicon
function vx_support_login_logo()
{
    $favicon_website = get_site_icon_url();
    echo '<style type="text/css">h1 a { background-image: url(';
    echo $favicon_website;
    echo ') !important; height: 100px !important; width: 100px !important; background-size: 100px !important;}</style>';
}

add_action('login_head', 'vx_support_login_logo');



//* Login Logo -> Link Text
add_filter('login_headertext', 'vx_support_login_url_title');
function vx_support_login_url_title()
{
    return 'Made with &hearts; by VX Media GmbH';
}



//* Login Logo -> Link Text Logo auf die Webseite leiten
add_filter('login_headerurl', 'vx_support_login_url');
function vx_support_login_url()
{
    return get_bloginfo('wpurl');
    //This line keeps the link on current website instead of WordPress.org
}



//* *Remove WordPress menu from admin bar*/
add_action('admin_bar_menu', 'remove_wp_logo', 999);
function remove_wp_logo($wp_admin_bar)
{
    $wp_admin_bar->remove_node('wp-logo');
}



// ENTFERNE ÜNÖTIGE WIDGETS AUD DASHBOARD
function remove_dashboard_meta()
{
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal'); //Removes the 'incoming links' widget
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal'); //Removes the 'plugins' widget
    remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Removes the 'WordPress News' widget
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal'); //Removes the secondary widget
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); //Removes the 'Quick Draft' widget
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side'); //Removes the 'Recent Drafts' widget
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal'); //Removes the 'Activity' widget
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //Removes the 'At a Glance' widget
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Removes the 'Activity' widget (since 3.8)
    remove_meta_box('e-dashboard-overview', 'dashboard', 'normal'); //Removes Elementor Dashboard
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal'); // Status der Webseite
    remove_meta_box('dce-dashboard-overview', 'dashboard', 'normal'); // Dynamico Dashboard
    remove_meta_box('example_dashboard_widget', 'dashboard', 'normal'); // Postman Dashboard
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal'); // Yoast Dashboard
}
add_action('admin_init', 'remove_dashboard_meta');

remove_action('welcome_panel', 'wp_welcome_panel');



// REMOVE HELP DROPDOWN (Rechts oben)
function wpse50787_remove_contextual_help()
{
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}
add_action('admin_head', 'wpse50787_remove_contextual_help');


// Entferne unötige Menü Punkte
add_action('admin_init', 'my_remove_menu_pages');
function my_remove_menu_pages()
{
    // Evtl. spätzer wenn nach der ID Sortiert werden muss
    // global $user_ID;
    // Wenn eigene ID dann Blende die Menü Punkte aus
    // if ($user_ID != 1) {

    // Entferne Wenn User Editor ist
    if (current_user_can('editor')) { //your user id

        remove_menu_page('edit-comments.php'); // Comments
        remove_menu_page('edit.php?post_type=elementor_library'); // Elementor Templates
        remove_menu_page('tools.php'); // Werkzeuge

        //remove_menu_page('edit.php'); // Posts
        //remove_menu_page('upload.php'); // Media
        //remove_menu_page('link-manager.php'); // Links
        //remove_menu_page('edit.php?post_type=page'); // Pages
        //remove_menu_page('plugins.php'); // Plugins
        //remove_menu_page('themes.php'); // Appearance
        //remove_menu_page('users.php'); // Users
        //remove_menu_page('options-general.php'); // Settings
        //remove_menu_page('edit.php'); // Posts
        //remove_menu_page('upload.php'); // Media
    }
}

?>