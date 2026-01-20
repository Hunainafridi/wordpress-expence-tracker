<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$database = new Expense_Tracker_Database();
$current_user_id = get_current_user_id();
$categories = $database->get_user_categories($current_user_id);
?>

<div class="wrap">
    <h1><?php _e('Expense Categories', 'expense-tracker'); ?></h1>
    
    <div class="category-management">
        <button class="button button-primary" id="add-category-btn">
            <?php _e('+ Add New Category', 'expense-tracker'); ?>
        </button>
        
        <div class="categories-list">
            <?php if (!empty($categories)): ?>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php _e('Color', 'expense-tracker'); ?></th>
                            <th><?php _e('Name', 'expense-tracker'); ?></th>
                            <th><?php _e('Description', 'expense-tracker'); ?></th>
                            <th><?php _e('Actions', 'expense-tracker'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td>
                                    <span class="category-color" style="background-color: <?php echo esc_attr($category->color); ?>;"></span>
                                </td>
                                <td><?php echo esc_html($category->name); ?></td>
                                <td><?php echo esc_html($category->description); ?></td>
                                <td>
                                    <button class="button button-small edit-category" data-id="<?php echo $category->id; ?>">
                                        <?php _e('Edit', 'expense-tracker'); ?>
                                    </button>
                                    <button class="button button-small button-link-delete delete-category" data-id="<?php echo $category->id; ?>">
                                        <?php _e('Delete', 'expense-tracker'); ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php _e('No categories found. Create your first category!', 'expense-tracker'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div id="category-modal" class="category-modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="category-modal-title"><?php _e('Add New Category', 'expense-tracker'); ?></h2>
        
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
