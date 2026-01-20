<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$database = new Expense_Tracker_Database();
$current_user_id = get_current_user_id();
$budgets = $database->get_user_budgets($current_user_id);
?>

<div class="wrap">
    <h1><?php _e('Budget Management', 'expense-tracker'); ?></h1>
    
    <div class="budget-management">
        <button class="button button-primary" id="add-budget-btn">
            <?php _e('+ Set Budget', 'expense-tracker'); ?>
        </button>
        
        <div class="budgets-list">
            <?php if (!empty($budgets)): ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php _e('Category', 'expense-tracker'); ?></th>
                            <th><?php _e('Amount', 'expense-tracker'); ?></th>
                            <th><?php _e('Period', 'expense-tracker'); ?></th>
                            <th><?php _e('Start Date', 'expense-tracker'); ?></th>
                            <th><?php _e('Actions', 'expense-tracker'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($budgets as $budget): ?>
                            <tr>
                                <td><?php echo esc_html($budget->category); ?></td>
                                <td><strong>$<?php echo number_format($budget->amount, 2); ?></strong></td>
                                <td><?php echo ucfirst(esc_html($budget->period)); ?></td>
                                <td><?php echo date('M d, Y', strtotime($budget->start_date)); ?></td>
                                <td>
                                    <button class="button button-small edit-budget" data-id="<?php echo $budget->id; ?>">
                                        <?php _e('Edit', 'expense-tracker'); ?>
                                    </button>
                                    <button class="button button-small button-link-delete delete-budget" data-id="<?php echo $budget->id; ?>">
                                        <?php _e('Delete', 'expense-tracker'); ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php _e('No budgets set yet. Create your first budget!', 'expense-tracker'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add/Edit Budget Modal -->
<div id="budget-modal" class="budget-modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="budget-modal-title"><?php _e('Set Budget', 'expense-tracker'); ?></h2>
        
        <form id="budget-form">
            <input type="hidden" id="budget-id" name="budget_id" value="">
            
            <div class="form-group">
                <label><?php _e('Category', 'expense-tracker'); ?></label>
                <select id="budget-category" name="category" required>
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
                <label><?php _e('Budget Amount', 'expense-tracker'); ?></label>
                <input type="number" id="budget-amount" name="amount" step="0.01" placeholder="0.00" required>
            </div>
            
            <div class="form-group">
                <label><?php _e('Period', 'expense-tracker'); ?></label>
                <select id="budget-period" name="period" required>
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="yearly">Yearly</option>
                </select>
            </div>
            
            <div class="form-group">
                <label><?php _e('Start Date', 'expense-tracker'); ?></label>
                <input type="date" id="budget-start-date" name="start_date" required>
            </div>
            
            <div class="form-group">
                <label><?php _e('End Date (Optional)', 'expense-tracker'); ?></label>
                <input type="date" id="budget-end-date" name="end_date">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button button-primary"><?php _e('Save Budget', 'expense-tracker'); ?></button>
                <button type="button" class="button cancel-btn"><?php _e('Cancel', 'expense-tracker'); ?></button>
            </div>
        </form>
    </div>
</div>
