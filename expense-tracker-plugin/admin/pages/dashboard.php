<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$database = new Expense_Tracker_Database();
$current_user_id = get_current_user_id();
$expenses = $database->get_user_expenses($current_user_id);

// Calculate total expenses
$total_amount = 0;
foreach ($expenses as $expense) {
    $total_amount += $expense->amount;
}
?>

<div class="wrap">
    <h1><?php _e('Expense Tracker Dashboard', 'expense-tracker'); ?></h1>
    
    <div class="expense-tracker-dashboard">
        
        <!-- Summary Cards -->
        <div class="dashboard-summary">
            <div class="summary-card">
                <h3><?php _e('Total Expenses', 'expense-tracker'); ?></h3>
                <p class="amount">$<?php echo number_format($total_amount, 2); ?></p>
            </div>
            <div class="summary-card">
                <h3><?php _e('This Month', 'expense-tracker'); ?></h3>
                <p class="amount">$0.00</p>
            </div>
            <div class="summary-card">
                <h3><?php _e('Total Records', 'expense-tracker'); ?></h3>
                <p class="amount"><?php echo count($expenses); ?></p>
            </div>
        </div>
        
        <!-- Add New Expense Button -->
        <div class="add-expense-section">
            <button class="button button-primary" id="open-add-expense-modal">
                <?php _e('+ Add New Expense', 'expense-tracker'); ?>
            </button>
        </div>
        
        <!-- Expenses Table -->
        <div class="expenses-table-section">
            <h2><?php _e('Recent Expenses', 'expense-tracker'); ?></h2>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php _e('Date', 'expense-tracker'); ?></th>
                        <th><?php _e('Description', 'expense-tracker'); ?></th>
                        <th><?php _e('Category', 'expense-tracker'); ?></th>
                        <th><?php _e('Amount', 'expense-tracker'); ?></th>
                        <th><?php _e('Payment Method', 'expense-tracker'); ?></th>
                        <th><?php _e('Actions', 'expense-tracker'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($expenses)): ?>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($expense->expense_date)); ?></td>
                                <td><?php echo esc_html($expense->description); ?></td>
                                <td><?php echo esc_html($expense->category); ?></td>
                                <td><strong>$<?php echo number_format($expense->amount, 2); ?></strong></td>
                                <td><?php echo esc_html($expense->payment_method); ?></td>
                                <td>
                                    <button class="button button-small edit-expense" data-id="<?php echo $expense->id; ?>">
                                        <?php _e('Edit', 'expense-tracker'); ?>
                                    </button>
                                    <button class="button button-small button-link-delete delete-expense" data-id="<?php echo $expense->id; ?>">
                                        <?php _e('Delete', 'expense-tracker'); ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">
                                <?php _e('No expenses found', 'expense-tracker'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<!-- Add/Edit Expense Modal -->
<div id="expense-modal" class="expense-modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title"><?php _e('Add New Expense', 'expense-tracker'); ?></h2>
        
        <form id="expense-form">
            <input type="hidden" id="expense-id" name="expense_id" value="">
            
            <div class="form-group">
                <label><?php _e('Date', 'expense-tracker'); ?></label>
                <input type="date" id="expense-date" name="expense_date" required>
            </div>
            
            <div class="form-group">
                <label><?php _e('Description', 'expense-tracker'); ?></label>
                <input type="text" id="expense-description" name="description" placeholder="<?php _e('Enter description', 'expense-tracker'); ?>" required>
            </div>
            
            <div class="form-group">
                <label><?php _e('Category', 'expense-tracker'); ?></label>
                <select id="expense-category" name="category" required>
                    <option value="">-- Select Category --</option>
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
                <input type="number" id="expense-amount" name="amount" step="0.01" placeholder="0.00" required>
            </div>
            
            <div class="form-group">
                <label><?php _e('Payment Method', 'expense-tracker'); ?></label>
                <select id="expense-payment" name="payment_method" required>
                    <option value="">-- Select Method --</option>
                    <option value="Cash">Cash</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Debit Card">Debit Card</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Check">Check</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button button-primary"><?php _e('Save Expense', 'expense-tracker'); ?></button>
                <button type="button" class="button cancel-btn"><?php _e('Cancel', 'expense-tracker'); ?></button>
            </div>
        </form>
    </div>
</div>
