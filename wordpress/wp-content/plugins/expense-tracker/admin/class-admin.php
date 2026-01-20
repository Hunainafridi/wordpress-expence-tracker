<?php

class Expense_Tracker_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Expense AJAX handlers
        add_action('wp_ajax_add_expense', array($this, 'handle_add_expense'));
        add_action('wp_ajax_edit_expense', array($this, 'handle_edit_expense'));
        add_action('wp_ajax_delete_expense', array($this, 'handle_delete_expense'));
        add_action('wp_ajax_get_expenses', array($this, 'handle_get_expenses'));
        
        // Category AJAX handlers
        add_action('wp_ajax_add_category', array($this, 'handle_add_category'));
        add_action('wp_ajax_edit_category', array($this, 'handle_edit_category'));
        add_action('wp_ajax_delete_category', array($this, 'handle_delete_category'));
        add_action('wp_ajax_get_categories', array($this, 'handle_get_categories'));
        
        // Budget AJAX handlers
        add_action('wp_ajax_add_budget', array($this, 'handle_add_budget'));
        add_action('wp_ajax_edit_budget', array($this, 'handle_edit_budget'));
        add_action('wp_ajax_delete_budget', array($this, 'handle_delete_budget'));
        add_action('wp_ajax_get_budgets', array($this, 'handle_get_budgets'));
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
    
    public function handle_add_category() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $category_id = $database->add_category(array(
            'user_id' => get_current_user_id(),
            'name' => sanitize_text_field($_POST['name']),
            'description' => sanitize_textarea_field($_POST['description'] ?? ''),
            'color' => sanitize_hex_color($_POST['color'] ?? '#3498db'),
            'icon' => sanitize_text_field($_POST['icon'] ?? '')
        ));
        
        wp_send_json_success(array('category_id' => $category_id));
    }
    
    public function handle_edit_category() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $database->update_category(intval($_POST['category_id']), array(
            'name' => sanitize_text_field($_POST['name']),
            'description' => sanitize_textarea_field($_POST['description'] ?? ''),
            'color' => sanitize_hex_color($_POST['color'] ?? '#3498db'),
            'icon' => sanitize_text_field($_POST['icon'] ?? '')
        ));
        
        wp_send_json_success();
    }
    
    public function handle_delete_category() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $database->delete_category(intval($_POST['category_id']));
        
        wp_send_json_success();
    }
    
    public function handle_get_categories() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $categories = $database->get_user_categories(get_current_user_id());
        
        wp_send_json_success($categories);
    }
    
    public function handle_add_budget() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $period = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : 'monthly';
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) && !empty($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        
        if (empty($category) || empty($amount) || empty($start_date)) {
            wp_send_json_error('Missing required fields');
            return;
        }
        
        $database = new Expense_Tracker_Database();
        $budget_id = $database->add_budget(array(
            'user_id' => get_current_user_id(),
            'category' => $category,
            'amount' => $amount,
            'period' => $period,
            'start_date' => $start_date,
            'end_date' => $end_date
        ));
        
        if (!$budget_id) {
            wp_send_json_error('Failed to save budget to database');
            return;
        }
        
        wp_send_json_success(array('budget_id' => $budget_id));
    }
    
    public function handle_edit_budget() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $budget_id = isset($_POST['budget_id']) ? intval($_POST['budget_id']) : 0;
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $period = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : 'monthly';
        $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) && !empty($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';
        
        if (empty($budget_id) || empty($category) || empty($amount) || empty($start_date)) {
            wp_send_json_error('Missing required fields');
            return;
        }
        
        $database = new Expense_Tracker_Database();
        $result = $database->update_budget($budget_id, array(
            'category' => $category,
            'amount' => $amount,
            'period' => $period,
            'start_date' => $start_date,
            'end_date' => $end_date
        ));
        
        if ($result === false) {
            wp_send_json_error('Failed to update budget');
            return;
        }
        
        wp_send_json_success();
    }
    
    public function handle_delete_budget() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $database->delete_budget(intval($_POST['budget_id']));
        
        wp_send_json_success();
    }
    
    public function handle_get_budgets() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $database = new Expense_Tracker_Database();
        $budgets = $database->get_user_budgets(get_current_user_id());
        
        wp_send_json_success($budgets);
    }
}
