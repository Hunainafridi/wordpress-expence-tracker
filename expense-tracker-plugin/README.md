# 💰 Expense Tracker - WordPress Plugin

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![License](https://img.shields.io/badge/license-GPL%20v2%20or%20later-green.svg)
![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.0+-blue.svg)

A powerful, professional-grade WordPress plugin for tracking personal and business expenses with real-time budget monitoring, detailed reporting, and comprehensive analytics.

## 🌟 Features

### Core Functionality
- 📊 **Smart Dashboard** - Real-time expense overview with summary cards
- 💸 **Complete Expense Management** - Add, edit, delete expenses with rich details
- 🏷️ **Custom Categories** - Create unlimited expense categories with color coding
- 💼 **Budget Tracking** - Set and monitor category budgets with visual progress
- 📈 **Advanced Reports** - Charts, analytics, and statistical insights
- 📱 **Fully Responsive** - Works seamlessly on desktop, tablet, and mobile devices

### Payment Methods
- Cash
- Credit Card
- Debit Card
- Bank Transfer
- Check

### Default Categories (Customizable)
- Food
- Transportation
- Utilities
- Entertainment
- Health
- Shopping
- Other

### Security Features
- ✅ AJAX nonce verification on all requests
- ✅ Role-based access control
- ✅ User capability checking
- ✅ Input sanitization and validation
- ✅ SQL injection protection
- ✅ User data isolation

## 📋 Requirements

- **WordPress**: 5.0 or higher
- **PHP**: 7.0 or higher
- **MySQL**: 5.6 or higher
- **jQuery**: Included with WordPress

## 🚀 Installation

### Method 1: Upload via WordPress Admin
1. Download the plugin as a ZIP file
2. In WordPress admin, go to **Plugins** → **Add New**
3. Click **Upload Plugin** and select the ZIP file
4. Click **Install Now** and then **Activate**

### Method 2: Manual Installation
1. Extract the plugin folder to `wp-content/plugins/`:
   ```bash
   git clone https://github.com/yourusername/expense-tracker-wordpress.git wp-content/plugins/expense-tracker
   ```
2. Activate the plugin through the WordPress Plugins admin menu
3. The plugin automatically creates database tables on first activation

## 💡 Quick Start

### For Administrators

#### Access the Plugin
1. Log in to WordPress admin dashboard
2. Look for **Expenses** in the left sidebar

#### Dashboard
- View total expenses summary
- See monthly spending overview
- Check recent expense records
- Quick action buttons for adding new expenses

#### Manage Expenses
1. Go to **Expenses → All Expenses**
2. Click **+ Add New Expense**
3. Fill in:
   - Date
   - Description
   - Category
   - Amount
   - Payment Method
4. Click **Save Expense**

#### Create Categories
1. Navigate to **Expenses → Categories**
2. Click **+ Add New Category**
3. Enter category details:
   - Name (e.g., "Groceries")
   - Description (optional)
   - Color (visual identifier)
4. Save

#### Set Budgets
1. Go to **Expenses → Budget**
2. Click **+ Set Budget**
3. Configure:
   - Category
   - Budget amount
   - Period (Monthly, Weekly, Yearly)
   - Start date
4. Save

#### View Reports
1. Go to **Expenses → Reports**
2. Use filters to refine data:
   - Date range
   - Category
3. View charts and statistics
4. Export as CSV (if enabled)

### For Frontend Users

#### Using Shortcodes

**Full Tracker Widget:**
```php
[expense_tracker]
```
Displays a complete expense management interface for logged-in users.

**Summary Widget:**
```php
[expense_summary]
```
Shows recent expenses summary with quick overview.

## 📁 Project Structure

```
expense-tracker/
│
├── 📄 expense-tracker.php              # Main plugin file
│
├── 📂 includes/                         # Core classes
│   ├── class-expense-tracker.php        # Main plugin class
│   └── class-database.php               # Database operations & queries
│
├── 📂 admin/                            # Admin interface
│   ├── class-admin.php                  # Admin functionality & AJAX handlers
│   └── pages/
│       ├── dashboard.php                # Dashboard overview
│       ├── categories.php               # Category management
│       ├── budget.php                   # Budget management
│       └── reports.php                  # Reports & analytics
│
├── 📂 public/                           # Frontend functionality
│   └── class-public.php                 # User-facing features
│
├── 📂 assets/                           # Stylesheets & scripts
│   ├── css/
│   │   ├── admin.css                   # Admin panel styles
│   │   └── public.css                  # Frontend styles
│   └── js/
│       ├── admin.js                    # Admin interactions
│       └── public.js                   # Frontend interactions
│
├── 📂 languages/                        # Internationalization
│   └── expense-tracker.pot              # Translation template
│
├── 📄 README.md                         # This file
├── 📄 SETUP_GUIDE.md                    # Detailed setup instructions
└── 📄 DEVELOPMENT.md                    # Developer documentation
```

## 🗄️ Database Schema

### wp_expenses
```sql
- id (bigint) - Primary key
- user_id (bigint) - User reference
- category (varchar) - Expense category
- description (longtext) - Detailed description
- amount (decimal) - Expense amount
- expense_date (date) - Transaction date
- payment_method (varchar) - How it was paid
- status (varchar) - pending/completed
- created_at (datetime) - Record creation timestamp
- updated_at (datetime) - Last update timestamp
```

### wp_expense_categories
```sql
- id (bigint) - Primary key
- user_id (bigint) - User reference
- name (varchar) - Category name
- description (longtext) - Category details
- color (varchar) - Hex color code
- icon (varchar) - Icon identifier
- created_at (datetime) - Creation timestamp
```

### wp_expense_budgets
```sql
- id (bigint) - Primary key
- user_id (bigint) - User reference
- category (varchar) - Budget category
- amount (decimal) - Budget limit
- period (varchar) - monthly/weekly/yearly
- start_date (date) - When budget starts
- end_date (date) - When budget ends (optional)
- created_at (datetime) - Creation timestamp
```

## 🔌 AJAX Endpoints

### Expense Operations
- `wp_ajax_add_expense` - Create new expense
- `wp_ajax_edit_expense` - Update existing expense
- `wp_ajax_delete_expense` - Remove expense
- `wp_ajax_get_expenses` - Retrieve expenses list

### Category Operations
- `wp_ajax_add_category` - Create category
- `wp_ajax_edit_category` - Update category
- `wp_ajax_delete_category` - Remove category
- `wp_ajax_get_categories` - Retrieve categories

### Budget Operations
- `wp_ajax_add_budget` - Create budget
- `wp_ajax_edit_budget` - Update budget
- `wp_ajax_delete_budget` - Remove budget
- `wp_ajax_get_budgets` - Retrieve budgets

## 🔐 Security

All AJAX requests are protected with:
- **Nonce verification** - CSRF protection
- **Capability checks** - `manage_options` required for admin features
- **Data sanitization** - All inputs are sanitized
- **Prepared statements** - SQL injection prevention
- **User isolation** - Users can only access their own data

## 📊 Hooks & Filters

The plugin provides several hooks for developers to extend functionality:

### Actions
```php
// Before expense is added
do_action('expense_tracker_before_add_expense', $expense_data);

// After expense is added
do_action('expense_tracker_after_add_expense', $expense_id);

// When tables are created
do_action('expense_tracker_tables_created');
```

### Filters
```php
// Filter expense data before saving
apply_filters('expense_tracker_expense_data', $data);

// Filter budget data before saving
apply_filters('expense_tracker_budget_data', $data);

// Filter report data
apply_filters('expense_tracker_report_data', $expenses);
```

## 🛠️ Development

### Setting Up Development Environment
1. Clone the repository
2. Install dependencies (if any)
3. Enable WordPress debug mode in `wp-config.php`:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
   ```

### Code Standards
- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- Use proper nonce verification for AJAX requests
- Sanitize all inputs, escape all outputs
- Use prepared statements for database queries

### Testing
Before submitting contributions:
1. Test in latest WordPress version
2. Test with different user roles
3. Verify AJAX functionality works
4. Check responsive design
5. Test in multiple browsers

See [DEVELOPMENT.md](DEVELOPMENT.md) for detailed developer guide.

## 📝 Changelog

### Version 1.0.0 (January 2026)
- ✅ Initial release
- ✅ Complete expense management
- ✅ Category management
- ✅ Budget tracking
- ✅ Reports & analytics
- ✅ AJAX-based interface
- ✅ Responsive design
- ✅ User data isolation

## 🤝 Contributing

Contributions are welcome! To contribute:

1. **Fork** the repository
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes** following code standards
4. **Test thoroughly**
5. **Commit changes**: `git commit -m 'Add amazing feature'`
6. **Push to branch**: `git push origin feature/amazing-feature`
7. **Open a Pull Request**

### Reporting Issues
- Search existing issues first
- Provide detailed description
- Include WordPress version, PHP version, and steps to reproduce
- Attach screenshots if relevant

## 📄 License

This project is licensed under the **GNU General Public License v2.0 or later** - see the [LICENSE](LICENSE) file for details.

Permissions:
- ✅ Commercial use
- ✅ Modification
- ✅ Distribution
- ✅ Private use

Conditions:
- 📋 Disclose source
- 📋 Include license
- 📋 State changes
- 📋 Same license

## 📚 Documentation

- **[SETUP_GUIDE.md](SETUP_GUIDE.md)** - Complete installation and usage guide
- **[DEVELOPMENT.md](DEVELOPMENT.md)** - Developer guide with hooks and filters
- **[PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)** - Project overview and features

## 🆘 Support

### Getting Help
- 📖 Check the [SETUP_GUIDE.md](SETUP_GUIDE.md)
- 🔍 Search [GitHub Issues](https://github.com/yourusername/expense-tracker-wordpress/issues)
- 💬 Open a new issue with detailed information

### Reporting Bugs
Include:
- WordPress version
- PHP version
- Detailed steps to reproduce
- Expected vs actual behavior
- Screenshots/error messages

## 🎯 Roadmap

Planned features for future releases:

- [ ] CSV/PDF export functionality
- [ ] Budget alert notifications
- [ ] Recurring expense templates
- [ ] Multi-currency support
- [ ] Mobile app integration
- [ ] Advanced filtering and search
- [ ] Expense splitting
- [ ] Team collaboration features

## 👥 Credits

**Development**: Your Name/Team
**Version**: 1.0.0
**Last Updated**: January 20, 2026

## 💬 Feedback

Have suggestions? Found a bug? Let us know!
- 🌟 Star this repository if you find it useful
- 🐛 Report issues on [GitHub Issues](https://github.com/yourusername/expense-tracker-wordpress/issues)
- 💡 Share feature ideas in discussions

## 📞 Contact

- **Email**: your-email@example.com
- **GitHub**: [@yourusername](https://github.com/yourusername)
- **Website**: https://yourwebsite.com

---

<div align="center">

**Made with ❤️ for the WordPress community**

[⬆ Back to top](#-expense-tracker---wordpress-plugin)

</div>

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
