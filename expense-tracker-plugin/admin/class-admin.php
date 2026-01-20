<?php

class Expense_Tracker_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_add_expense', array($this, 'handle_add_expense'));
        add_action('wp_ajax_edit_expense', array($this, 'handle_edit_expense'));
        add_action('wp_ajax_delete_expense', array($this, 'handle_delete_expense'));
        add_action('wp_ajax_get_expenses', array($this, 'handle_get_expenses'));
    }
    
    public function register_admin_menu() {
        add_menu_page(
            __('Expense Tracker', 'expense-tracker'),
            __('Expenses', 'expense-tracker'),
            'manage_options',
            'expense-tracker',
            array($this, 'render_dashboard'),
            'dashicons-money',
            6
        );
        
        add_submenu_page(
            'expense-tracker',
            __('All Expenses', 'expense-tracker'),
            __('All Expenses', 'expense-tracker'),
            'manage_options',
            'expense-tracker',
            array($this, 'render_dashboard')
        );
        
        add_submenu_page(
            'expense-tracker',
            __('Categories', 'expense-tracker'),
            __('Categories', 'expense-tracker'),
            'manage_options',
            'expense-categories',
            array($this, 'render_categories')
        );
        
        add_submenu_page(
            'expense-tracker',
            __('Budget', 'expense-tracker'),
            __('Budget', 'expense-tracker'),
            'manage_options',
            'expense-budget',
            array($this, 'render_budget')
        );
        
        add_submenu_page(
            'expense-tracker',
            __('Reports', 'expense-tracker'),
            __('Reports', 'expense-tracker'),
            'manage_options',
            'expense-reports',
            array($this, 'render_reports')
        );
    }
    
    public function register_settings() {
        // Register settings if needed
    }
    
    public function render_dashboard() {
        require_once EXPENSE_TRACKER_PLUGIN_DIR . 'admin/pages/dashboard.php';
    }
    
    public function render_categories() {
        require_once EXPENSE_TRACKER_PLUGIN_DIR . 'admin/pages/categories.php';
    }
    
    public function render_budget() {
        require_once EXPENSE_TRACKER_PLUGIN_DIR . 'admin/pages/budget.php';
    }
    
    public function render_reports() {
        require_once EXPENSE_TRACKER_PLUGIN_DIR . 'admin/pages/reports.php';
    }
    
    public function handle_add_expense() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $expense_id = $database->add_expense(array(
            'user_id' => get_current_user_id(),
            'category' => sanitize_text_field($_POST['category']),
            'description' => sanitize_textarea_field($_POST['description']),
            'amount' => floatval($_POST['amount']),
            'expense_date' => sanitize_text_field($_POST['expense_date']),
            'payment_method' => sanitize_text_field($_POST['payment_method']),
            'status' => 'completed'
        ));
        
        wp_send_json_success(array('expense_id' => $expense_id));
    }
    
    public function handle_edit_expense() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $database->update_expense(intval($_POST['expense_id']), array(
            'category' => sanitize_text_field($_POST['category']),
            'description' => sanitize_textarea_field($_POST['description']),
            'amount' => floatval($_POST['amount']),
            'expense_date' => sanitize_text_field($_POST['expense_date']),
            'payment_method' => sanitize_text_field($_POST['payment_method'])
        ));
        
        wp_send_json_success();
    }
    
    public function handle_delete_expense() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $database->delete_expense(intval($_POST['expense_id']));
        
        wp_send_json_success();
    }
    
    public function handle_get_expenses() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $expenses = $database->get_user_expenses(get_current_user_id(), $_POST);
        
        wp_send_json_success($expenses);
    }
}
