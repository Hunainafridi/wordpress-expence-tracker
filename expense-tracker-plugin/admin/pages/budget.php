<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Budget Management', 'expense-tracker'); ?></h1>
    
    <div class="budget-management">
        <button class="button button-primary" id="add-budget-btn">
            <?php _e('+ Set Budget', 'expense-tracker'); ?>
        </button>
        
        <div class="budgets-grid">
            <!-- Budgets will be loaded here -->
        </div>
    </div>
</div>

<!-- Add/Edit Budget Modal -->
<div id="budget-modal" class="budget-modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2><?php _e('Set Budget', 'expense-tracker'); ?></h2>
        
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
            
            <div class="form-actions">
                <button type="submit" class="button button-primary"><?php _e('Save Budget', 'expense-tracker'); ?></button>
                <button type="button" class="button cancel-btn"><?php _e('Cancel', 'expense-tracker'); ?></button>
            </div>
        </form>
    </div>
</div>
