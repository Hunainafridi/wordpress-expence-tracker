<?php

class Expense_Tracker_Public {
    
    public function __construct() {
        add_shortcode('expense_tracker', array($this, 'render_tracker_shortcode'));
        add_shortcode('expense_summary', array($this, 'render_summary_shortcode'));
        add_action('wp_ajax_nopriv_get_user_expenses', array($this, 'handle_get_user_expenses'));
        add_action('wp_ajax_user_add_expense', array($this, 'handle_user_add_expense'));
    }
    
    public function render_tracker_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to access the expense tracker.', 'expense-tracker') . '</p>';
        }
        
        $database = new Expense_Tracker_Database();
        $current_user_id = get_current_user_id();
        $expenses = $database->get_user_expenses($current_user_id);
        
        ob_start();
        ?>
        <div class="expense-tracker-frontend">
            <div class="tracker-header">
                <h2><?php _e('My Expenses', 'expense-tracker'); ?></h2>
                <button class="btn btn-primary" id="add-expense-btn">
                    <?php _e('+ Add Expense', 'expense-tracker'); ?>
                </button>
            </div>
            
            <div class="expenses-list">
                <?php if (!empty($expenses)): ?>
                    <?php foreach ($expenses as $expense): ?>
                        <div class="expense-item">
                            <div class="expense-info">
                                <h4><?php echo esc_html($expense->description); ?></h4>
                                <p class="expense-category"><?php echo esc_html($expense->category); ?></p>
                                <p class="expense-date"><?php echo date('F d, Y', strtotime($expense->expense_date)); ?></p>
                            </div>
                            <div class="expense-amount">$<?php echo number_format($expense->amount, 2); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-expenses"><?php _e('No expenses recorded yet.', 'expense-tracker'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Add Expense Modal for Frontend -->
        <div id="add-expense-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3><?php _e('Add New Expense', 'expense-tracker'); ?></h3>
                
                <form id="user-expense-form">
                    <div class="form-group">
                        <label><?php _e('Date', 'expense-tracker'); ?></label>
                        <input type="date" name="expense_date" required>
                    </div>
                    
                    <div class="form-group">
                        <label><?php _e('Description', 'expense-tracker'); ?></label>
                        <input type="text" name="description" required>
                    </div>
                    
                    <div class="form-group">
                        <label><?php _e('Category', 'expense-tracker'); ?></label>
                        <select name="category" required>
                            <option value="Food">Food</option>
                            <option value="Transportation">Transportation</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Health">Health</option>
                            <option value="Shopping">Shopping</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label><?php _e('Amount', 'expense-tracker'); ?></label>
                        <input type="number" name="amount" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label><?php _e('Payment Method', 'expense-tracker'); ?></label>
                        <select name="payment_method" required>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Debit Card">Debit Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary"><?php _e('Save Expense', 'expense-tracker'); ?></button>
                </form>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function render_summary_shortcode($atts) {
        if (!is_user_logged_in()) {
            return '<p>' . __('Please log in to view your expense summary.', 'expense-tracker') . '</p>';
        }
        
        $database = new Expense_Tracker_Database();
        $current_user_id = get_current_user_id();
        $expenses = $database->get_user_expenses($current_user_id, array('limit' => 5));
        
        $total = 0;
        foreach ($expenses as $expense) {
            $total += $expense->amount;
        }
        
        ob_start();
        ?>
        <div class="expense-summary-widget">
            <div class="summary-box">
                <h3><?php _e('Expense Summary', 'expense-tracker'); ?></h3>
                <p class="total-expenses">
                    <?php _e('Total Expenses:', 'expense-tracker'); ?> 
                    <strong>$<?php echo number_format($total, 2); ?></strong>
                </p>
                <p class="recent-count">
                    <?php _e('Recent Transactions:', 'expense-tracker'); ?> 
                    <strong><?php echo count($expenses); ?></strong>
                </p>
                <a href="<?php echo admin_url('admin.php?page=expense-tracker'); ?>" class="btn btn-small">
                    <?php _e('View Full Report', 'expense-tracker'); ?>
                </a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function handle_get_user_expenses() {
        if (!is_user_logged_in()) {
            wp_send_json_error('Not logged in');
        }
        
        $database = new Expense_Tracker_Database();
        $expenses = $database->get_user_expenses(get_current_user_id());
        
        wp_send_json_success($expenses);
    }
    
    public function handle_user_add_expense() {
        check_ajax_referer('expense-tracker-nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error('Not logged in');
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
}
