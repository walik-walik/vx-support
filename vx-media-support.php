<?php
/*
Plugin Name: VX Media - Support & Security
Plugin URI: https://www.vx-media.de
Description: WP durch leistungsfähige und professionelle Codes erweitern.
Version: 2.0.8
Author: VX Media GmbH
Author URI: https://www.vx-media.de
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function vx_support_get_current_version() {
    $plugin_data = get_file_data(__FILE__, array('Version' => 'Version'));
    return $plugin_data['Version'];
}

function vx_support_enqueue_styles() {
    wp_enqueue_style('admin-styles-vx-support', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action('admin_enqueue_scripts', 'vx_support_enqueue_styles');

function vx_support_enqueue_scripts() {
    wp_enqueue_script('vx-support-ajax', plugin_dir_url(__FILE__) . 'js/vx-support.js', array('jquery'), null, true);
    wp_localize_script('vx-support-ajax', 'vxSupport', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('vx_support_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'vx_support_enqueue_scripts');

function vx_media_dashboard_widgets() {
    wp_add_dashboard_widget('vx_media_help_widget', 'VX Media GmbH | Hilfe & Support', 'vx_media_dashboard_help');
    wp_add_dashboard_widget('vx_media_status_widget', 'VX Media GmbH | Status der Webseite', 'vx_media_dashboard_status');
}
add_action('wp_dashboard_setup', 'vx_media_dashboard_widgets');

function vx_media_dashboard_help() {
    $versionPlugin = vx_support_get_current_version();
    ?>
    <div class="vx-support-wrapper">
        <p>Herzlich Willkommen in Ihrem Backend! Sie benötigen Hilfe? <br><br>Sie erreichen uns unter: <br>
            <strong>Mobil:</strong> 07303/9569185<br>
            <strong>Mail:</strong> support@vx-media.de<br><br>
            Ihr Design & Code Team<br>
            <strong>VX Media GmbH</strong>
        </p>
        <span>Version <?php echo $versionPlugin; ?></span>
        <br><br>
        <button id="vx-check-updates" class="button button-primary">Auf Updates prüfen</button>
        <div id="vx-update-message"></div>
    </div>
    <?php
}

function vx_media_dashboard_status() {
    if (get_option('vx_license') == 'YNpbWuC98qRMtj2j') {
        $checkImage = plugin_dir_url(__FILE__) . 'css/images/check-circle-solid.svg';
        ?>
        <div class="status-headline">24/7 Service & Wartung Premium</div>
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
        <?php
    }
}

add_action('admin_init', 'set_dashboard_meta_order');
function set_dashboard_meta_order() {
    $id = get_current_user_id();
    $meta_value = array(
        'normal' => 'vx_media_dashboard_widgets',
        'side' => 'vx_media_status_widget',
        'column3' => '',
        'column4' => '',
    );
    update_user_meta($id, 'meta-box-order_dashboard', $meta_value);
}

add_filter('admin_footer_text', 'vx_support_footer');
function vx_support_footer() {
    echo '<span id="footer-thankyou">Made with &hearts; by VX Media GmbH</span>';
}

add_action('login_head', 'vx_support_login_logo');
function vx_support_login_logo() {
    $favicon_website = get_site_icon_url();
    echo '<style type="text/css">h1 a { background-image: url(' . esc_url($favicon_website) . ') !important; height: 100px !important; width: 100px !important; background-size: 100px !important;}</style>';
}

add_filter('login_headertext', 'vx_support_login_url_title');
function vx_support_login_url_title() {
    return 'Made with &hearts; by VX Media GmbH';
}

add_filter('login_headerurl', 'vx_support_login_url');
function vx_support_login_url() {
    return get_bloginfo('wpurl');
}

add_action('admin_bar_menu', 'remove_wp_logo', 999);
function remove_wp_logo($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
}

add_action('admin_init', 'remove_dashboard_meta');
function remove_dashboard_meta() {
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_primary', 'dashboard', 'normal');
    remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
    remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    remove_meta_box('e-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    remove_meta_box('dce-dashboard-overview', 'dashboard', 'normal');
    remove_meta_box('example_dashboard_widget', 'dashboard', 'normal');
    remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'normal');
}

remove_action('welcome_panel', 'wp_welcome_panel');

add_action('admin_head', 'wpse50787_remove_contextual_help');
function wpse50787_remove_contextual_help() {
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}

add_action('admin_init', 'my_remove_menu_pages');
function my_remove_menu_pages() {
    if (current_user_can('editor')) {
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php?post_type=elementor_library');
        remove_menu_page('tools.php');
    }
}

add_action('admin_notices', 'blackhole_tools_admin_notice');
function blackhole_tools_admin_notice() {
    if (get_option('vx_license') != 'YNpbWuC98qRMtj2j') {
        $class = 'notice notice-error';
        $message = __('⚠⚠⚠ ACHTUNG - KEIN WARTUNGS VERTRAG! KEINE UPDATES, BACKUP & WIEDERHERSTELLUNG ⚠⚠⚠', 'vx-media');
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }
}

add_action('admin_notices', 'vx_blog_public_admin_notice');
function vx_blog_public_admin_notice() {
    if (get_option('blog_public') != 1) {
        $class = 'notice notice-warning';
        $message = __('⚠⚠⚠ ACHTUNG - Sichtbarkeit für Suchmaschinen ist auf NO-INDEX ⚠⚠⚠', 'vx-media');
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }
}

add_action('admin_menu', 'my_admin_menu');
function my_admin_menu() {
    add_submenu_page(
        'tools.php',
        __('VX Safety', 'vx-media'),
        __('VX Safety', 'vx-media'),
        'manage_options',
        'vxSettings-page',
        'my_admin_page_contents',
        'dashicons-schedule',
        3
    );
}

function my_admin_page_contents() {
    ?>
    <h1><?php esc_html_e('VX Media GmbH', 'vx-media'); ?></h1>
    <form method="POST" action="options.php">
        <?php
        settings_fields('vxSettings-page');
        do_settings_sections('vxSettings-page');
        submit_button();
        ?>
    </form>
    <?php
}

add_action('admin_init', 'my_settings_init');

function my_settings_init() {
    add_settings_section(
        'vxSettings_page_setting_section',
        __('Settings', 'vx-media'),
        '__return_false', // Diese Funktion gibt false zurück und entfernt so den Einführungstext
        'vxSettings-page'
    );

    register_setting('vxSettings-page', 'vx_license');
}

function vx_license_setting() {
    ?>
    <input type="password" id="vx_license" name="vx_license" value="<?php echo get_option('vx_license'); ?>">
    <?php
}
?>
