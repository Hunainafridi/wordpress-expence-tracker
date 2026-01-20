# Expense Tracker WordPress Plugin

A complete WordPress plugin for managing personal and business expenses with budget tracking, reporting, and categorization.

## Features

### Core Features
- 💰 **Expense Management**: Add, edit, and delete expenses with detailed information
- 📊 **Dashboard**: Real-time overview of your expenses
- 🏷️ **Categories**: Create custom expense categories
- 💼 **Budget Tracking**: Set and monitor budgets by category
- 📈 **Reports & Analytics**: Visual reports with charts and statistics
- 👥 **User-Specific**: Each user can track their own expenses
- 📱 **Responsive Design**: Works perfectly on desktop and mobile devices

### Payment Methods
- Cash
- Credit Card
- Debit Card
- Bank Transfer
- Check

### Default Categories
- Food
- Transportation
- Utilities
- Entertainment
- Health
- Shopping
- Other

## Installation

1. Download the plugin folder to your WordPress plugins directory:
   ```
   wp-content/plugins/expense-tracker-plugin/
   ```

2. Activate the plugin through the WordPress admin dashboard

3. The plugin will automatically create the necessary database tables on activation

## Usage

### For Administrators

1. Navigate to **Expenses** in the WordPress admin menu
2. **Dashboard**: View your expense summary and recent transactions
3. **All Expenses**: Manage all expenses (add, edit, delete)
4. **Categories**: Create and manage custom categories
5. **Budget**: Set budget limits for different categories
6. **Reports**: View analytics and generate reports

### For Regular Users

Use the following shortcodes on any page or post:

#### Display Expense Tracker
```
[expense_tracker]
```
Shows the complete expense tracker interface for logged-in users.

#### Display Expense Summary Widget
```
[expense_summary]
```
Shows a quick summary of recent expenses.

## Database Structure

### wp_expenses Table
- `id` - Unique expense identifier
- `user_id` - Associated user ID
- `category` - Expense category
- `description` - Detailed description
- `amount` - Expense amount
- `expense_date` - Date of expense
- `payment_method` - How the expense was paid
- `status` - Pending or completed
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### wp_expense_categories Table
- `id` - Category identifier
- `user_id` - Associated user ID
- `name` - Category name
- `description` - Category description
- `color` - Color code for display
- `icon` - Icon identifier
- `created_at` - Creation timestamp

### wp_expense_budgets Table
- `id` - Budget identifier
- `user_id` - Associated user ID
- `category` - Budget category
- `amount` - Budget amount
- `period` - Budget period (monthly, weekly, yearly)
- `start_date` - Budget start date
- `end_date` - Budget end date
- `created_at` - Creation timestamp

## File Structure

```
expense-tracker-plugin/
├── expense-tracker.php           # Main plugin file
├── includes/
│   ├── class-expense-tracker.php # Main class
│   └── class-database.php        # Database operations
├── admin/
│   ├── class-admin.php           # Admin functionality
│   └── pages/
│       ├── dashboard.php         # Admin dashboard
│       ├── categories.php        # Categories management
│       ├── budget.php            # Budget management
│       └── reports.php           # Reports and analytics
├── public/
│   └── class-public.php          # Frontend functionality
├── assets/
│   ├── css/
│   │   ├── admin.css            # Admin styles
│   │   └── public.css           # Frontend styles
│   └── js/
│       ├── admin.js             # Admin scripts
│       └── public.js            # Frontend scripts
└── languages/                    # Translation files

```

## AJAX Endpoints

### Admin AJAX Actions
- `add_expense` - Add new expense
- `edit_expense` - Update existing expense
- `delete_expense` - Delete expense
- `get_expenses` - Retrieve expenses

### Frontend AJAX Actions
- `get_user_expenses` - Get user's expenses
- `user_add_expense` - Add new user expense

## Hooks and Filters

### Action Hooks
- `expense_tracker_before_add_expense` - Before adding expense
- `expense_tracker_after_add_expense` - After adding expense
- `expense_tracker_before_delete_expense` - Before deleting expense
- `expense_tracker_after_delete_expense` - After deleting expense

### Filter Hooks
- `expense_tracker_expense_data` - Filter expense data before saving
- `expense_tracker_categories` - Filter available categories
- `expense_tracker_payment_methods` - Filter payment methods

## Permissions

- **Manage Expenses**: Requires `manage_options` capability (Administrators only)
- **View Personal Expenses**: Logged-in users can view their own expenses
- **Add Personal Expenses**: Logged-in users can add their own expenses

## Security Features

- AJAX nonce verification
- Capability checking
- Data sanitization (text, textarea, numbers)
- SQL injection protection with prepared statements
- User-specific data isolation

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher

## Settings

The plugin works out of the box with default categories. To extend functionality:

1. **Add Custom Categories**: Navigate to Expenses > Categories
2. **Set Budget Limits**: Go to Expenses > Budget
3. **View Reports**: Check Expenses > Reports for detailed analytics

## Troubleshooting

### Expenses not saving?
- Verify you have admin permissions
- Check browser console for JavaScript errors
- Ensure nonce validation is passing

### Database tables not created?
- Deactivate and reactivate the plugin
- Check WordPress error logs
- Ensure database user has CREATE TABLE permissions

### Styling issues?
- Clear browser cache
- Verify CSS files are loading (check Network tab)
- Check for CSS conflicts with your theme

## Future Enhancements

- [ ] Recurring expenses
- [ ] Receipt uploads
- [ ] Multiple user support for shared budgets
- [ ] Email notifications for budget alerts
- [ ] CSV/PDF export functionality
- [ ] Mobile app integration
- [ ] Advanced filtering and search
- [ ] Expense sharing between users
- [ ] Multi-currency support
- [ ] Integration with accounting software

## Support

For issues, feature requests, or bug reports, please contact the plugin author.

## License

This plugin is licensed under the GPL v2 or later license.

## Changelog

### Version 1.0.0
- Initial release
- Core expense tracking functionality
- Dashboard and reporting features
- Budget management system
- Category management
