<?php

class Expense_Tracker {
    
    public function run() {
        // Load admin files
        if (is_admin()) {
            require_once EXPENSE_TRACKER_PLUGIN_DIR . 'admin/class-admin.php';
            new Expense_Tracker_Admin();
        }
        
        // Load public files
        require_once EXPENSE_TRACKER_PLUGIN_DIR . 'public/class-public.php';
        new Expense_Tracker_Public();
        
        // Register hooks
        $this->register_hooks();
    }
    
    private function register_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('init', array($this, 'load_textdomain'));
    }
    
    public function enqueue_public_assets() {
        wp_enqueue_style('expense-tracker-public', EXPENSE_TRACKER_PLUGIN_URL . 'assets/css/public.css', array(), EXPENSE_TRACKER_VERSION);
        wp_enqueue_script('expense-tracker-public', EXPENSE_TRACKER_PLUGIN_URL . 'assets/js/public.js', array('jquery'), EXPENSE_TRACKER_VERSION, true);
        
        wp_localize_script('expense-tracker-public', 'expenseTrackerAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('expense-tracker-nonce')
        ));
    }
    
    public function enqueue_admin_assets() {
        wp_enqueue_style('expense-tracker-admin', EXPENSE_TRACKER_PLUGIN_URL . 'assets/css/admin.css', array(), EXPENSE_TRACKER_VERSION);
        wp_enqueue_script('expense-tracker-admin', EXPENSE_TRACKER_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), EXPENSE_TRACKER_VERSION, true);
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js', array(), '3.0.0', true);
        
        // Localize admin script with nonce and ajax url
        wp_localize_script('expense-tracker-admin', 'expenseTrackerAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('expense-tracker-nonce')
        ));
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('expense-tracker', false, dirname(plugin_basename(EXPENSE_TRACKER_PLUGIN_FILE)) . '/languages');
    }
}
