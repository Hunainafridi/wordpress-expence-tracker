<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Expense Categories', 'expense-tracker'); ?></h1>
    
    <div class="category-management">
        <button class="button button-primary" id="add-category-btn">
            <?php _e('+ Add New Category', 'expense-tracker'); ?>
        </button>
        
        <div id="categories-list" class="categories-grid">
            <!-- Categories will be loaded here -->
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div id="category-modal" class="category-modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2><?php _e('Add New Category', 'expense-tracker'); ?></h2>
        
        <form id="category-form">
            <input type="hidden" id="category-id" name="category_id" value="">
            
            <div class="form-group">
                <label><?php _e('Category Name', 'expense-tracker'); ?></label>
                <input type="text" id="category-name" name="name" placeholder="<?php _e('e.g., Groceries', 'expense-tracker'); ?>" required>
            </div>
            
            <div class="form-group">
                <label><?php _e('Description', 'expense-tracker'); ?></label>
                <textarea id="category-description" name="description" placeholder="<?php _e('Enter category description', 'expense-tracker'); ?>"></textarea>
            </div>
            
            <div class="form-group">
                <label><?php _e('Color', 'expense-tracker'); ?></label>
                <input type="color" id="category-color" name="color" value="#3498db">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button button-primary"><?php _e('Save Category', 'expense-tracker'); ?></button>
                <button type="button" class="button cancel-btn"><?php _e('Cancel', 'expense-tracker'); ?></button>
            </div>
        </form>
    </div>
</div>
