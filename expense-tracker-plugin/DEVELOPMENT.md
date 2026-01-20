<?php
/**
 * Development & Extension Guide for Expense Tracker Plugin
 * 
 * This file documents how to extend and customize the Expense Tracker plugin
 */

/**
 * EXTENDING THE PLUGIN
 */

// 1. Adding Custom Hooks
// =====================

/**
 * Example: Add custom code before expense is added
 * 
 * In your theme's functions.php:
 */

add_action('expense_tracker_before_add_expense', 'my_custom_function', 10, 1);

function my_custom_function($expense_data) {
    // $expense_data contains:
    // - user_id
    // - category
    // - description
    // - amount
    // - expense_date
    // - payment_method
    
    // Your custom code here
    error_log('Expense added: ' . $expense_data['amount']);
}

// 2. Custom Filters
// =================

/**
 * Filter expense data before saving
 */

add_filter('expense_tracker_expense_data', 'my_filter_function', 10, 1);

function my_filter_function($data) {
    // Modify data before saving
    $data['amount'] = round($data['amount'], 2);
    return $data;
}

// 3. Adding Custom Database Tables
// ==================================

/**
 * Example: Create a custom table for expense tags
 */

function my_custom_table_creation() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    $table = $wpdb->prefix . 'expense_tags';
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        expense_id bigint(20) NOT NULL,
        tag_name varchar(100) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY expense_id (expense_id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Hook into activation
add_action('expense_tracker_tables_created', 'my_custom_table_creation');

// 4. Creating Custom Admin Pages
// ===============================

/**
 * Example: Add custom submenu page
 */

add_action('admin_menu', 'add_custom_expense_page');

function add_custom_expense_page() {
    add_submenu_page(
        'expense-tracker',
        __('Custom Reports', 'expense-tracker'),
        __('Custom Reports', 'expense-tracker'),
        'manage_options',
        'custom-expense-reports',
        'render_custom_reports_page'
    );
}

function render_custom_reports_page() {
    $database = new Expense_Tracker_Database();
    $expenses = $database->get_user_expenses(get_current_user_id());
    
    // Render your custom page
    echo '<div class="wrap"><h1>Custom Reports</h1></div>';
}

// 5. Creating Custom Shortcodes
// ==============================

/**
 * Example: Custom shortcode for expense statistics
 */

add_shortcode('expense_stats', 'render_expense_stats_shortcode');

function render_expense_stats_shortcode($atts) {
    $database = new Expense_Tracker_Database();
    $expenses = $database->get_user_expenses(get_current_user_id());
    
    $total = array_sum(array_map(function($e) { return $e->amount; }, $expenses));
    
    return '<div class="expense-stats">
        <p>Total Expenses: $' . number_format($total, 2) . '</p>
    </div>';
}

// 6. AJAX Integration
// ===================

/**
 * Example: Create custom AJAX endpoint
 */

add_action('wp_ajax_custom_expense_action', 'handle_custom_expense_action');

function handle_custom_expense_action() {
    check_ajax_referer('expense-tracker-nonce', 'nonce');
    
    if (!is_user_logged_in()) {
        wp_send_json_error('Not authorized');
    }
    
    // Process your request
    $result = do_something_custom($_POST['data']);
    
    wp_send_json_success($result);
}

// 7. Creating REST API Endpoints
// ===============================

/**
 * Example: Register custom REST endpoint
 */

add_action('rest_api_init', 'register_custom_expense_endpoints');

function register_custom_expense_endpoints() {
    register_rest_route('expense-tracker/v1', '/user-expenses', array(
        'methods' => 'GET',
        'callback' => 'get_user_expenses_rest',
        'permission_callback' => function() {
            return is_user_logged_in();
        }
    ));
}

function get_user_expenses_rest($request) {
    $database = new Expense_Tracker_Database();
    $expenses = $database->get_user_expenses(get_current_user_id());
    
    return rest_ensure_response($expenses);
}

// 8. Custom Styling
// =================

/**
 * Example: Add custom styling for your extension
 */

add_action('admin_enqueue_scripts', 'load_custom_expense_styles');

function load_custom_expense_styles() {
    wp_enqueue_style('custom-expense', plugins_url('/css/custom.css', __FILE__));
}

// 9. Database Query Examples
// ==========================

/**
 * Get expenses for a specific date range
 */

function get_expenses_by_date_range($user_id, $start_date, $end_date) {
    global $wpdb;
    
    $table = $wpdb->prefix . 'expenses';
    $query = $wpdb->prepare(
        "SELECT * FROM $table 
         WHERE user_id = %d 
         AND expense_date BETWEEN %s AND %s
         ORDER BY expense_date DESC",
        $user_id,
        $start_date,
        $end_date
    );
    
    return $wpdb->get_results($query);
}

/**
 * Get total expenses by category
 */

function get_expenses_by_category($user_id, $start_date = null, $end_date = null) {
    global $wpdb;
    
    $table = $wpdb->prefix . 'expenses';
    $query = "SELECT category, SUM(amount) as total 
              FROM $table 
              WHERE user_id = %d";
    
    $params = array($user_id);
    
    if ($start_date && $end_date) {
        $query .= " AND expense_date BETWEEN %s AND %s";
        $params[] = $start_date;
        $params[] = $end_date;
    }
    
    $query .= " GROUP BY category";
    
    return $wpdb->get_results($wpdb->prepare($query, $params));
}

/**
 * Get monthly expense summary
 */

function get_monthly_summary($user_id, $month, $year) {
    global $wpdb;
    
    $table = $wpdb->prefix . 'expenses';
    $start_date = "{$year}-{$month}-01";
    $end_date = date('Y-m-t', strtotime($start_date));
    
    $query = $wpdb->prepare(
        "SELECT SUM(amount) as total, COUNT(*) as count
         FROM $table
         WHERE user_id = %d
         AND expense_date BETWEEN %s AND %s",
        $user_id,
        $start_date,
        $end_date
    );
    
    return $wpdb->get_row($query);
}

// 10. Creating Custom Widgets
// ===========================

/**
 * Example: Create expense dashboard widget
 */

class Expense_Tracker_Dashboard_Widget {
    public static function init() {
        add_action('wp_dashboard_setup', array(__CLASS__, 'add_widget'));
    }
    
    public static function add_widget() {
        wp_add_dashboard_widget(
            'expense_tracker_widget',
            __('Expense Tracker', 'expense-tracker'),
            array(__CLASS__, 'render_widget')
        );
    }
    
    public static function render_widget() {
        $database = new Expense_Tracker_Database();
        $expenses = $database->get_user_expenses(get_current_user_id(), array('limit' => 5));
        
        echo '<div class="expense-widget">';
        echo '<h4>Recent Expenses</h4>';
        foreach ($expenses as $expense) {
            echo '<div class="expense-item">';
            echo '<span>' . $expense->description . '</span>';
            echo '<span>$' . number_format($expense->amount, 2) . '</span>';
            echo '</div>';
        }
        echo '</div>';
    }
}

// 11. Plugin Settings Page
// ========================

/**
 * Example: Create plugin settings
 */

add_action('admin_menu', 'add_expense_settings_page');

function add_expense_settings_page() {
    add_submenu_page(
        'expense-tracker',
        __('Settings', 'expense-tracker'),
        __('Settings', 'expense-tracker'),
        'manage_options',
        'expense-settings',
        'render_expense_settings'
    );
}

function render_expense_settings() {
    ?>
    <div class="wrap">
        <h1><?php _e('Expense Tracker Settings', 'expense-tracker'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('expense_tracker_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label><?php _e('Default Currency', 'expense-tracker'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="expense_currency" 
                               value="<?php echo esc_attr(get_option('expense_currency', '$')); ?>" />
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// 12. Testing & Debugging
// =======================

/**
 * Example: Debug function to log expense operations
 */

function expense_tracker_debug_log($message, $data = null) {
    if (defined('WP_DEBUG') && WP_DEBUG === true) {
        $log_message = '[Expense Tracker] ' . $message;
        if ($data) {
            $log_message .= ' - ' . json_encode($data);
        }
        error_log($log_message);
    }
}

// Usage:
// expense_tracker_debug_log('Expense created', $expense_data);

/**
 * Example: Query debugging
 */

function expense_tracker_debug_query() {
    global $wpdb;
    
    if (defined('SAVEQUERIES') && SAVEQUERIES) {
        echo '<pre>';
        print_r($wpdb->queries);
        echo '</pre>';
    }
}

// 13. Best Practices
// ==================

/**
 * DO:
 * - Use prepared statements for all database queries
 * - Check user capabilities before operations
 * - Sanitize input data
 * - Use proper nonce verification
 * - Follow WordPress coding standards
 * - Use actions and filters for extensibility
 * - Cache expensive queries
 * - Use transients for temporary data
 * 
 * DON'T:
 * - Direct database queries without wpdb
 * - Skip capability checks
 * - Use $_POST/$_GET directly without sanitization
 * - Hard-code IDs or values
 * - Create plugin features without hooks
 * - Ignore backwards compatibility
 */

// Example of caching:
function get_user_expenses_cached($user_id) {
    $cache_key = 'expense_tracker_' . $user_id;
    $expenses = wp_cache_get($cache_key);
    
    if (false === $expenses) {
        $database = new Expense_Tracker_Database();
        $expenses = $database->get_user_expenses($user_id);
        wp_cache_set($cache_key, $expenses, '', 3600); // 1 hour
    }
    
    return $expenses;
}

// 14. Internationalization
// ========================

/**
 * Example: Using translations in your extension
 */

// In your code:
$message = __('Expense saved successfully', 'expense-tracker');
$message = _e('Delete this expense?', 'expense-tracker');

// String translation:
$translated = __('Your translatable string', 'expense-tracker');

// Plural forms:
$message = sprintf(
    _n(
        '%d expense recorded',
        '%d expenses recorded',
        $count,
        'expense-tracker'
    ),
    $count
);

?>
