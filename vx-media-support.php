<?php
/*
Plugin Name: VX Media - Support & Security
Plugin URI: https://www.vx-media.de
Description: WP durch leistungsfähige und professionelle Codes erweitern.
Version: 2.1.1
Author: VX Media GmbH
Author URI: https://www.vx-media.de
*/

// Sicherheitsabfrage: Verhindert direkten Aufruf der Datei.
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Gibt die aktuelle Plugin-Version zurück.
 *
 * @return string Die Plugin-Version.
 */
function vx_support_get_current_version() {
    $plugin_data = get_file_data(__FILE__, array('Version' => 'Version'));
    return $plugin_data['Version'];
}

/**
 * Lädt die benötigten CSS-Dateien für das Admin-Panel.
 */
function vx_support_enqueue_styles() {
    wp_enqueue_style('admin-styles-vx-support', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action('admin_enqueue_scripts', 'vx_support_enqueue_styles');

/**
 * Lädt die benötigten JavaScript-Dateien und übergibt AJAX-Parameter.
 */
function vx_support_enqueue_scripts() {
    wp_enqueue_script('vx-support-ajax', plugin_dir_url(__FILE__) . 'js/vx-support.js', array('jquery'), null, true);
    wp_localize_script('vx-support-ajax', 'vxSupport', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('vx_support_nonce')
    ));
}
add_action('admin_enqueue_scripts', 'vx_support_enqueue_scripts');

/**
 * Fügt die Dashboard-Widgets hinzu.
 */
function vx_media_dashboard_widgets() {
    wp_add_dashboard_widget('vx_media_help_widget', 'VX Media GmbH | Hilfe & Support', 'vx_media_dashboard_help');
    wp_add_dashboard_widget('vx_media_status_widget', 'VX Media GmbH | Status der Webseite', 'vx_media_dashboard_status');
}
add_action('wp_dashboard_setup', 'vx_media_dashboard_widgets');

/**
 * Inhalt des Hilfe & Support Dashboard-Widgets.
 */
function vx_media_dashboard_help() {
    $versionPlugin = vx_support_get_current_version();
    ?>
    <div class="vx-support-wrapper">
        <p>Herzlich Willkommen in Ihrem Backend! Sie benötigen Hilfe? <br><br>
            Sie erreichen uns unter: <br>
            <strong>Mobil:</strong> 07303/9569185<br>
            <strong>Mail:</strong> support@vx-media.de<br><br>
            Ihr Design & Code Team<br>
            <strong>VX Media GmbH</strong>
        </p>
        <span>Version <?php echo $versionPlugin; ?></span>
        <br><br>
        <!-- <button id="vx-check-updates" class="button button-primary">Auf Updates prüfen</button> -->
        <!-- <div id="vx-update-message"></div> -->
    </div>
    <?php
}

/**
 * Inhalt des Status Dashboard-Widgets.
 * Zeigt je nach Lizenzstatus unterschiedliche Inhalte an.
 */
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
                <!-- <li><img src="<?php echo $checkImage; ?>"> Bildoptimierung und Spamprävention</li> -->
                <li><img src="<?php echo $checkImage; ?>"> Wiederherstellung einer gehackten Website</li>
            </ul>
        </div>
        <div class="notfall-hotline"><span>NOTFALL HOTLINE</span>0162 / 928 5 928</div>
        <?php
    } else {
        $exclamationImage = plugin_dir_url(__FILE__) . 'css/images/exclamation-circle-solid.svg';
        $exclamationTriangelImage = plugin_dir_url(__FILE__) . 'css/images/exclamation-triangle-solid.svg';
        ?>
        <div class="status-headline lizens-inaktiv">
            <img class="pulse" src="<?php echo $exclamationTriangelImage; ?>"> KEIN WARTUNGS VERTRAG 
            <img class="pulse" src="<?php echo $exclamationTriangelImage; ?>">
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

/**
 * Setzt die Reihenfolge der Dashboard-Widgets für den aktuellen Benutzer.
 */
add_action('admin_init', 'set_dashboard_meta_order');
function set_dashboard_meta_order() {
    $id = get_current_user_id();
    $meta_value = array(
        'normal'  => 'vx_media_dashboard_widgets',
        'side'    => 'vx_media_status_widget',
        'column3' => '',
        'column4' => '',
    );
    update_user_meta($id, 'meta-box-order_dashboard', $meta_value);
}

/**
 * Ändert den Footer-Text im Admin-Panel.
 */
add_filter('admin_footer_text', 'vx_support_footer');
function vx_support_footer() {
    echo '<span id="footer-thankyou">Made with &hearts; by VX Media GmbH</span>';
}

/**
 * Passt das Login-Logo an und nutzt das Website-Favicon.
 */
add_action('login_head', 'vx_support_login_logo');
function vx_support_login_logo() {
    $favicon_website = get_site_icon_url();
    echo '<style type="text/css">
            h1 a { 
                background-image: url(' . esc_url($favicon_website) . ') !important; 
                height: 100px !important; 
                width: 100px !important; 
                background-size: 100px !important;
            }
          </style>';
}

/**
 * Setzt den Titel für die Login-Seite.
 *
 * @return string Der Login-Seitentitel.
 */
add_filter('login_headertext', 'vx_support_login_url_title');
function vx_support_login_url_title() {
    return 'Made with &hearts; by VX Media GmbH';
}

/**
 * Setzt die URL für den Login-Link.
 *
 * @return string Die URL der Website.
 */
add_filter('login_headerurl', 'vx_support_login_url');
function vx_support_login_url() {
    return get_bloginfo('wpurl');
}

/**
 * Entfernt das WordPress-Logo aus der Admin-Bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar Die Admin-Bar.
 */
add_action('admin_bar_menu', 'remove_wp_logo', 999);
function remove_wp_logo($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
}

/**
 * Entfernt überflüssige Meta-Boxen vom Dashboard.
 */
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

// Entfernt das Standard-Willkommens-Panel.
remove_action('welcome_panel', 'wp_welcome_panel');

/**
 * Entfernt die Hilfetexte (Kontextbezogene Hilfe) im Admin-Bereich.
 */
add_action('admin_head', 'wpse50787_remove_contextual_help');
function wpse50787_remove_contextual_help() {
    $screen = get_current_screen();
    $screen->remove_help_tabs();
}

/**
 * Entfernt bestimmte Menü-Seiten für Benutzer mit der Rolle "Editor".
 */
add_action('admin_init', 'my_remove_menu_pages');
function my_remove_menu_pages() {
    if (current_user_can('editor')) {
        remove_menu_page('edit-comments.php');
        remove_menu_page('edit.php?post_type=elementor_library');
        remove_menu_page('tools.php');
    }
}

/**
 * Zeigt eine Admin-Notiz, wenn kein gültiger Wartungsvertrag (Lizenz) vorhanden ist.
 */
add_action('admin_notices', 'blackhole_tools_admin_notice');
function blackhole_tools_admin_notice() {
    if (get_option('vx_license') != 'YNpbWuC98qRMtj2j') {
        $class = 'notice notice-error';
        $message = __('⚠⚠⚠ ACHTUNG - KEIN WARTUNGS VERTRAG! KEINE UPDATES, BACKUP & WIEDERHERSTELLUNG ⚠⚠⚠', 'vx-media');
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }
}

/**
 * Zeigt eine Admin-Notiz, wenn die Sichtbarkeit der Website für Suchmaschinen deaktiviert ist.
 */
add_action('admin_notices', 'vx_blog_public_admin_notice');
function vx_blog_public_admin_notice() {
    if (get_option('blog_public') != 1) {
        $class = 'notice notice-warning';
        $message = __('⚠⚠⚠ ACHTUNG - Sichtbarkeit für Suchmaschinen ist auf NO-INDEX ⚠⚠⚠', 'vx-media');
        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
    }
}

/**
 * Fügt ein Untermenü im Tools-Menü hinzu für die VX Safety Einstellungen.
 */
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

/**
 * Inhalt der Admin-Einstellungsseite.
 */
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

/**
 * Initialisiert die Einstellungen für die VX Safety Seite.
 */
add_action('admin_init', 'my_settings_init');
function my_settings_init() {
    // Hinzufügen einer Einstellungssektion ohne Einführungstext.
    add_settings_section(
        'vxSettings_page_setting_section',
        __('Settings', 'vx-media'),
        '__return_false',
        'vxSettings-page'
    );

    // Registrierung der Lizenzoption.
    register_setting('vxSettings-page', 'vx_license');

    // Hinzufügen des Lizenzfeldes zur Einstellungssektion.
    add_settings_field(
        'vx_license_field',
        __('Lizenz Schlüssel', 'vx-media'),
        'vx_license_setting',
        'vxSettings-page',
        'vxSettings_page_setting_section'
    );
}

/**
 * Ausgabe des Eingabefeldes für den Lizenz Schlüssel.
 */
function vx_license_setting() {
    ?>
    <input type="password" id="vx_license" name="vx_license" value="<?php echo esc_attr(get_option('vx_license')); ?>">
    <?php
}

// Deaktiviert E-Mail-Benachrichtigungen für Core, Plugin und Theme Updates.
add_filter('auto_core_update_send_email', '__return_false');
add_filter('auto_plugin_update_send_email', '__return_false');
add_filter('auto_theme_update_send_email', '__return_false');

?>