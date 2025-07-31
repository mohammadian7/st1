<?php
/**
 * Plugin Name: باشگاه نمایندگان استیل البرز
 * Plugin URI: https://steelalborz.com
 * Description: سیستم مدیریت باشگاه فروش و وفاداری نمایندگان و نصابان شرکت استیل البرز
 * Version: 1.0.0
 * Author: Steel Alborz Team
 * License: GPL v2 or later
 * Text Domain: steelalborz-club
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('STEELALBORZ_CLUB_VERSION', '1.0.0');
define('STEELALBORZ_CLUB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('STEELALBORZ_CLUB_PLUGIN_URL', plugin_dir_url(__FILE__));
define('STEELALBORZ_CLUB_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-steelalborz-club.php';
require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-api-manager.php';
require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-wallet-manager.php';
require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-points-manager.php';
require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-rewards-manager.php';
require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-admin-menu.php';
require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-shortcodes.php';
require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-user-roles.php';

require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-data-import.php';

// Initialize the plugin
function steelalborz_club_init() {
    $plugin = new SteelAlborzClub();
    $plugin->init();
    
    // Run database migrations for existing installations
    require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-database.php';
    $database = new SteelAlborzClubDatabase();
    $database->run_migrations();
}
add_action('plugins_loaded', 'steelalborz_club_init');

// Activation hook
register_activation_hook(__FILE__, 'steelalborz_club_activate');
function steelalborz_club_activate() {
    // Create database tables
    require_once STEELALBORZ_CLUB_PLUGIN_DIR . 'includes/class-database.php';
    $database = new SteelAlborzClubDatabase();
    $database->create_tables();
    
    // Set default options
    steelalborz_club_set_default_options();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'steelalborz_club_deactivate');
function steelalborz_club_deactivate() {
    // Cleanup if needed
}

// Set default options
function steelalborz_club_set_default_options() {
    $default_options = array(
        'reward_amount_installation' => 50000, // 50,000 Toman
        'points_installation' => 100,
        'points_happy_call' => 50,
        'points_referral' => 200,
        'min_withdrawal_amount' => 100000, // 100,000 Toman
        'min_transfer_amount' => 50000, // 50,000 Toman
        'sorooshan_api_url' => 'http://guarantee.steelalborz.com:8081/api',
        'sorooshan_username' => 'steal@5',
        'sorooshan_password' => 'Alborz_03',
        'bank_api_url' => '',
        'bank_api_key' => '',
        'bank_transfer_url' => 'https://10.10.10.112:38453/v0.3/obh/api/pisp/transfer',
        'bank_name' => '',
        'national_code' => '',
        'source_account' => '',
        'transfer_type' => 'PAYA',
        'babat_code' => 'HOGHOGH',
        'bank_token_url' => '',
        'bank_client_id' => '',
        'bank_client_secret' => '',
        'jibit_api_url' => '',
        'jibit_api_key' => '',
        'email_notification_enabled' => true,
        'sms_notification_enabled' => true,
        'enable_registration' => false
    );
    
    foreach ($default_options as $key => $value) {
        if (get_option('steelalborz_club_' . $key) === false) {
            update_option('steelalborz_club_' . $key, $value);
        }
    }
} 