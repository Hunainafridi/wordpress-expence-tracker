<?php
// Test budget saving functionality
require 'wp-load.php';

if (!is_user_logged_in()) {
    echo "User not logged in. Testing with admin user...";
    wp_set_current_user(1);
}

// Test data
$test_budget = array(
    'user_id' => get_current_user_id(),
    'category' => 'Food',
    'amount' => 500.00,
    'period' => 'monthly',
    'start_date' => date('Y-m-d'),
    'end_date' => ''
);

// Include the database class
require_once plugin_dir_path(__FILE__) . 'expense-tracker/includes/class-database.php';

$database = new Expense_Tracker_Database();

echo "Current User ID: " . get_current_user_id() . "\n";
echo "Test Data: " . json_encode($test_budget) . "\n";

// Try to add budget
$budget_id = $database->add_budget($test_budget);

if ($budget_id) {
    echo "SUCCESS: Budget saved with ID: " . $budget_id . "\n";
} else {
    echo "FAILED: Budget was not saved\n";
    global $wpdb;
    echo "Last Error: " . $wpdb->last_error . "\n";
}
?>
