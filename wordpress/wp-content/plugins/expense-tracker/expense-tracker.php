<?php
/**
 * Plugin Name: Expense Tracker
 * Plugin URI: https://example.com/expense-tracker
 * Description: A complete WordPress plugin to track expenses with budget management and reporting
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: expense-tracker
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('EXPENSE_TRACKER_VERSION', '1.0.0');
define('EXPENSE_TRACKER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('EXPENSE_TRACKER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EXPENSE_TRACKER_PLUGIN_FILE', __FILE__);

// Include required files
require_once EXPENSE_TRACKER_PLUGIN_DIR . 'includes/class-expense-tracker.php';
require_once EXPENSE_TRACKER_PLUGIN_DIR . 'includes/class-database.php';

// Initialize the plugin
function expense_tracker_init() {
    $expense_tracker = new Expense_Tracker();
    $expense_tracker->run();
}

add_action('plugins_loaded', 'expense_tracker_init');

// Register activation and deactivation hooks
register_activation_hook(__FILE__, 'expense_tracker_activate');
register_deactivation_hook(__FILE__, 'expense_tracker_deactivate');

function expense_tracker_activate() {
    $database = new Expense_Tracker_Database();
    $database->create_tables();
}

function expense_tracker_deactivate() {
    // Cleanup if needed
}
