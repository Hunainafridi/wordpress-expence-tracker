/* Expense Tracker Admin JavaScript */

jQuery(document).ready(function($) {
    const modal = document.getElementById('expense-modal');
    const addBtn = document.getElementById('open-add-expense-modal');
    const closeBtn = document.querySelector('.close');
    const form = document.getElementById('expense-form');
    
    // Open modal
    if (addBtn) {
        addBtn.onclick = function() {
            document.getElementById('modal-title').innerText = 'Add New Expense';
            form.reset();
            document.getElementById('expense-id').value = '';
            modal.style.display = 'block';
            document.getElementById('expense-date').valueAsDate = new Date();
        }
    }
    
    // Close modal
    if (closeBtn) {
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }
    }
    
    // Close on cancel button
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.onclick = function() {
            modal.style.display = 'none';
        }
    });
    
    // Close when clicking outside modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const expenseId = document.getElementById('expense-id').value;
            const action = expenseId ? 'edit_expense' : 'add_expense';
            
            const formData = new FormData(form);
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
                        modal.style.display = 'none';
                        location.reload();
                    } else {
                        alert('Error: ' + response.data);
                    }
                },
                error: function() {
                    alert('An error occurred while saving the expense.');
                }
            });
        });
    }
    
    // Edit expense
    document.querySelectorAll('.edit-expense').forEach(btn => {
        btn.addEventListener('click', function() {
            const expenseId = this.getAttribute('data-id');
            const row = this.closest('tr');
            const cells = row.querySelectorAll('td');
            
            // Populate form with expense data
            document.getElementById('expense-id').value = expenseId;
            document.getElementById('expense-date').value = cells[0].innerText; // You may need to adjust this
            document.getElementById('expense-description').value = cells[1].innerText;
            document.getElementById('expense-category').value = cells[2].innerText;
            document.getElementById('expense-amount').value = cells[3].innerText.replace('$', '').replace(',', '');
            document.getElementById('expense-payment').value = cells[4].innerText;
            
            document.getElementById('modal-title').innerText = 'Edit Expense';
            modal.style.display = 'block';
        });
    });
    
    // Delete expense
    document.querySelectorAll('.delete-expense').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this expense?')) {
                const expenseId = this.getAttribute('data-id');
                
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
                            location.reload();
                        } else {
                            alert('Error deleting expense');
                        }
                    }
                });
            }
        });
    });
});
