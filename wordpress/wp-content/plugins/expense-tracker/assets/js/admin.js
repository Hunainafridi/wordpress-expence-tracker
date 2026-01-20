/* Expense Tracker Admin JavaScript */

jQuery(document).ready(function($) {
    
    // ===== EXPENSE MANAGEMENT =====
    
    const expenseModal = document.getElementById('expense-modal');
    const expenseAddBtn = document.getElementById('open-add-expense-modal');
    const expenseForm = document.getElementById('expense-form');
    let currentEditingExpenseId = null;
    
    // Open expense modal
    if (expenseAddBtn) {
        expenseAddBtn.addEventListener('click', function() {
            document.getElementById('modal-title').textContent = 'Add New Expense';
            expenseForm.reset();
            currentEditingExpenseId = null;
            document.getElementById('expense-date').valueAsDate = new Date();
            expenseModal.style.display = 'block';
        });
    }
    
    // Handle expense form submission
    if (expenseForm) {
        expenseForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form data
            const date = document.getElementById('expense-date').value;
            const description = document.getElementById('expense-description').value;
            const category = document.getElementById('expense-category').value;
            const amount = document.getElementById('expense-amount').value;
            const payment = document.getElementById('expense-payment').value;
            
            if (!date || !description || !category || !amount || !payment) {
                alert('Please fill in all required fields.');
                return;
            }
            
            const action = currentEditingExpenseId ? 'edit_expense' : 'add_expense';
            const formData = new FormData(this);
            formData.append('action', action);
            formData.append('nonce', expenseTrackerAjax.nonce);
            
            $.ajax({
                url: expenseTrackerAjax.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        expenseModal.style.display = 'none';
                        alert('Expense saved successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.data || 'Unknown error'));
                        console.log('AJAX Error:', response);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while saving the expense.');
                    console.log('AJAX Error:', error, xhr.responseText);
                }
            });
        });
    }
    
    // Edit expense
    $(document).on('click', '.edit-expense', function() {
        const expenseId = $(this).data('id');
        const row = $(this).closest('tr');
        const cells = row.find('td');
        
        // Fetch full expense data via AJAX to get proper date format
        $.ajax({
            url: expenseTrackerAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_expenses',
                nonce: expenseTrackerAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    const expenses = response.data;
                    const expense = expenses.find(e => e.id == expenseId);
                    if (expense) {
                        document.getElementById('expense-id').value = expense.id;
                        document.getElementById('expense-date').value = expense.expense_date;
                        document.getElementById('expense-description').value = expense.description;
                        document.getElementById('expense-category').value = expense.category;
                        document.getElementById('expense-amount').value = expense.amount;
                        document.getElementById('expense-payment').value = expense.payment_method;
                        document.getElementById('modal-title').textContent = 'Edit Expense';
                        currentEditingExpenseId = expense.id;
                        expenseModal.style.display = 'block';
                    }
                }
            }
        });
    });
    
    // Delete expense
    $(document).on('click', '.delete-expense', function() {
        if (confirm('Are you sure you want to delete this expense?')) {
            const expenseId = $(this).data('id');
            
            $.ajax({
                url: expenseTrackerAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_expense',
                    expense_id: expenseId,
                    nonce: expenseTrackerAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('Expense deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting expense');
                    }
                }
            });
        }
    });
    
    // Close modals
    closeAllModals();
    
    function closeAllModals() {
        const closeButtons = document.querySelectorAll('.modal-content .close');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.category-modal, .budget-modal, .expense-modal').style.display = 'none';
            });
        });
        
        const cancelButtons = document.querySelectorAll('.cancel-btn');
        cancelButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.closest('.category-modal, .budget-modal, .expense-modal').style.display = 'none';
            });
        });
        
        window.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('.category-modal, .budget-modal, .expense-modal');
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        });
    }
    
    // ===== CATEGORY MANAGEMENT =====
    
    const categoryModal = document.getElementById('category-modal');
    const categoryAddBtn = document.getElementById('add-category-btn');
    const categoryForm = document.getElementById('category-form');
    let currentEditingCategoryId = null;
    
    if (categoryAddBtn) {
        categoryAddBtn.addEventListener('click', function() {
            document.getElementById('category-modal-title').textContent = 'Add New Category';
            categoryForm.reset();
            currentEditingCategoryId = null;
            categoryModal.style.display = 'block';
        });
    }
    
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form data
            const name = document.getElementById('category-name').value;
            
            if (!name) {
                alert('Please enter a category name.');
                return;
            }
            
            const action = currentEditingCategoryId ? 'edit_category' : 'add_category';
            const formData = new FormData(this);
            formData.append('action', action);
            formData.append('nonce', expenseTrackerAjax.nonce);
            
            $.ajax({
                url: expenseTrackerAjax.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        categoryModal.style.display = 'none';
                        alert('Category saved successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.data || 'Unknown error'));
                        console.log('AJAX Error:', response);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while saving the category.');
                    console.log('AJAX Error:', error, xhr.responseText);
                }
            });
        });
    }
    
    $(document).on('click', '.edit-category', function() {
        const categoryId = $(this).data('id');
        
        $.ajax({
            url: expenseTrackerAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_categories',
                nonce: expenseTrackerAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    const categories = response.data;
                    const category = categories.find(c => c.id == categoryId);
                    if (category) {
                        document.getElementById('category-id').value = category.id;
                        document.getElementById('category-name').value = category.name;
                        document.getElementById('category-description').value = category.description;
                        document.getElementById('category-color').value = category.color;
                        document.getElementById('category-modal-title').textContent = 'Edit Category';
                        currentEditingCategoryId = category.id;
                        categoryModal.style.display = 'block';
                    }
                }
            }
        });
    });
    
    $(document).on('click', '.delete-category', function() {
        if (confirm('Are you sure you want to delete this category?')) {
            const categoryId = $(this).data('id');
            
            $.ajax({
                url: expenseTrackerAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_category',
                    category_id: categoryId,
                    nonce: expenseTrackerAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('Category deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting category');
                    }
                }
            });
        }
    });
    
    // ===== BUDGET MANAGEMENT =====
    
    const budgetModal = document.getElementById('budget-modal');
    const budgetAddBtn = document.getElementById('add-budget-btn');
    const budgetForm = document.getElementById('budget-form');
    let currentEditingBudgetId = null;
    
    if (budgetAddBtn) {
        budgetAddBtn.addEventListener('click', function() {
            document.getElementById('budget-modal-title').textContent = 'Set Budget';
            budgetForm.reset();
            currentEditingBudgetId = null;
            document.getElementById('budget-start-date').valueAsDate = new Date();
            budgetModal.style.display = 'block';
        });
    }
    
    if (budgetForm) {
        budgetForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form data
            const category = document.getElementById('budget-category').value;
            const amount = document.getElementById('budget-amount').value;
            const period = document.getElementById('budget-period').value;
            const startDate = document.getElementById('budget-start-date').value;
            
            if (!category || !amount || !period || !startDate) {
                alert('Please fill in all required fields.');
                return;
            }
            
            const action = currentEditingBudgetId ? 'edit_budget' : 'add_budget';
            const formData = new FormData(this);
            formData.append('action', action);
            formData.append('nonce', expenseTrackerAjax.nonce);
            
            $.ajax({
                url: expenseTrackerAjax.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        budgetModal.style.display = 'none';
                        alert('Budget saved successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + (response.data || 'Unknown error'));
                        console.log('AJAX Error:', response);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while saving the budget.');
                    console.log('AJAX Error:', error, xhr.responseText);
                }
            });
        });
    }
    
    $(document).on('click', '.edit-budget', function() {
        const budgetId = $(this).data('id');
        
        $.ajax({
            url: expenseTrackerAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_budgets',
                nonce: expenseTrackerAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    const budgets = response.data;
                    const budget = budgets.find(b => b.id == budgetId);
                    if (budget) {
                        document.getElementById('budget-id').value = budget.id;
                        document.getElementById('budget-category').value = budget.category;
                        document.getElementById('budget-amount').value = budget.amount;
                        document.getElementById('budget-period').value = budget.period;
                        document.getElementById('budget-start-date').value = budget.start_date;
                        document.getElementById('budget-end-date').value = budget.end_date || '';
                        document.getElementById('budget-modal-title').textContent = 'Edit Budget';
                        currentEditingBudgetId = budget.id;
                        budgetModal.style.display = 'block';
                    }
                }
            }
        });
    });
    
    $(document).on('click', '.delete-budget', function() {
        if (confirm('Are you sure you want to delete this budget?')) {
            const budgetId = $(this).data('id');
            
            $.ajax({
                url: expenseTrackerAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_budget',
                    budget_id: budgetId,
                    nonce: expenseTrackerAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert('Budget deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting budget');
                    }
                }
            });
        }
    });
});
