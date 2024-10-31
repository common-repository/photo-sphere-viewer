<?php

/**
 * Plugin Name: Photo Sphere Viewer
 * Plugin URI: https://wordpress.org/plugins/photo-sphere-viewer/
 * Description: Photo Sphere Viewer renders 360° panoramas shots with Photo Sphere, the new camera mode of Android 4.2 Jelly Bean and above. It also supports cube panoramas.
 * Version: 1.3.0
 * Author: WPXERO
 * Author URI: https://github.com/wpxero/
 * Text Domain: photo-sphere-viewer
 * License: GPL3
 * Domain Path: /languages/
 * Elementor requires at least: 3.0.0
 * Elementor tested up to: 3.25.1
 */



namespace Elementor;

if (!defined('ABSPATH')) {
    exit(__('Direct Access is not allowed', 'photo-sphere-viewer'));
}



// Some pre define value for easy use
define('PSV_VER', '1.3.0');
define('PSV__FILE__', __FILE__);
define('PSV_URL', plugins_url('/', PSV__FILE__));
define('PSV_ASSETS_URL', PSV_URL . 'assets/');


final class PhotoSphereViewer {

    const VERSION                   = PSV_VER;
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION       = '7.4';

    private static $_instance = null;

    public static function instance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        load_plugin_textdomain('photo-sphere-viewer', false, plugin_dir_path(__FILE__) . '/languages');
        require(dirname(__FILE__) . '/shortcodes/shortcodes.php');

        // load assets
        add_action('wp_enqueue_scripts', [$this, 'assets_enqueue']);
        add_action('elementor/widgets/register', [$this, 'init_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_new_category']);

        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }
    }

    public function admin_notice_minimum_php_version() {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Photo Sphere Viewer 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'photo-sphere-viewer'),
            '<strong>' . esc_html__('Photo Sphere Viewer', 'photo-sphere-viewer') . '</strong>',
            '<strong>' . esc_html__('PHP', 'photo-sphere-viewer') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version() {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Photo Sphere Viewer 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'photo-sphere-viewer'),
            '<strong>' . esc_html__('Photo Sphere Viewer', 'photo-sphere-viewer') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'photo-sphere-viewer') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_missing_main_plugin() {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Photo Sphere Viewer 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'photo-sphere-viewer'),
            '<strong>' . esc_html__('Photo Sphere Viewer', 'photo-sphere-viewer') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'photo-sphere-viewer') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }


    /**
     * !Register Categories
     */
    public function register_new_category($elements_manager) {
        $elements_manager->add_category(
            'photo-sphere-viewer',
            [
                'title' => __('Photo Sphere Viewer', 'photo-sphere-viewer'),
            ]
        );
    }

    /**
     * !enqueue assets
     */
    public function assets_enqueue() {
        wp_enqueue_script('three-js', PSV_ASSETS_URL . 'vendor/js/three.min.js', null, '', true);
        wp_enqueue_script('browser-js', PSV_ASSETS_URL . 'vendor/js/browser.min.js', null, '', true);

        wp_enqueue_style('photo-sphere-viewer-css', PSV_ASSETS_URL . 'vendor/css/photo-sphere-viewer.min.css', null, '4.6.1');
        wp_enqueue_script('photo-sphere-viewer', PSV_ASSETS_URL . 'vendor/js/photo-sphere-viewer.min.js', null, '4.6.1', true);

        wp_enqueue_style('psv-custom-css', PSV_ASSETS_URL . 'css/style.css', null, self::VERSION);
        wp_enqueue_script('psv-custom-js', PSV_ASSETS_URL . 'js/main.js', ['jquery'], self::VERSION, true);
    }

    /**
     * ! Widgets Init
     */
    public function init_widgets($widgets_manager) {
        require(dirname(__FILE__) . '/widgets/widget.php');

        $widgets_manager->register(new \Elementor\Photo_Sphere_Viewer());
    }
}

PhotoSphereViewer::instance();
