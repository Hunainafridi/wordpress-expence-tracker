/* Expense Tracker Frontend JavaScript */

jQuery(document).ready(function($) {
    const modal = document.getElementById('add-expense-modal');
    const addBtn = document.getElementById('add-expense-btn');
    const closeBtn = document.querySelector('.close');
    const form = document.getElementById('user-expense-form');
    
    // Open modal
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            form.reset();
            modal.style.display = 'block';
            document.querySelector('#user-expense-form input[name="expense_date"]').valueAsDate = new Date();
        });
    }
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    
    // Close when clicking outside modal
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            formData.append('action', 'user_add_expense');
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
});
