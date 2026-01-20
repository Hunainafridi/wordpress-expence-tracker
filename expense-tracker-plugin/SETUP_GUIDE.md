# Expense Tracker Plugin - Setup Guide

## Quick Start

### Step 1: Installation
1. Copy the `expense-tracker-plugin` folder to your WordPress plugins directory:
   ```
   wp-content/plugins/
   ```

2. Log in to your WordPress admin dashboard

3. Go to **Plugins** and find "Expense Tracker"

4. Click **Activate**

### Step 2: Initial Setup

Once activated, you'll see a new **Expenses** menu in your WordPress admin sidebar.

#### Access Admin Features
- **Dashboard**: Overview of all expenses
- **All Expenses**: Manage your expenses (CRUD operations)
- **Categories**: Create custom expense categories
- **Budget**: Set budget limits for categories
- **Reports**: View charts and analytics

#### Create Categories
1. Go to **Expenses → Categories**
2. Click **+ Add New Category**
3. Enter category details:
   - Name (e.g., "Groceries")
   - Description (optional)
   - Color (for display)
4. Save

#### Set Budgets
1. Go to **Expenses → Budget**
2. Click **+ Set Budget**
3. Configure:
   - Select category
   - Set amount
   - Choose period (Monthly, Weekly, Yearly)
   - Set start date
4. Save

### Step 3: Adding Expenses

#### Admin Dashboard
1. Go to **Expenses → All Expenses**
2. Click **+ Add New Expense**
3. Fill in the form:
   - Date
   - Description
   - Category
   - Amount
   - Payment Method
4. Click **Save Expense**

#### Frontend (For Regular Users)
Add this shortcode to any page or post:
```
[expense_tracker]
```

Users can then:
1. Click **+ Add Expense**
2. Fill in expense details
3. Submit the form

### Step 4: View Reports

1. Go to **Expenses → Reports**
2. View:
   - Pie chart of expenses by category
   - Spending trends chart
   - Summary statistics
3. Use filters to refine data:
   - Start/End date
   - Category
4. Export as CSV (if enabled)

## Using Shortcodes

### [expense_tracker]
Display the full expense tracker for logged-in users.

**Example:**
```
[expense_tracker]
```

**Output:**
- Expense list with all user's expenses
- Add new expense button and modal

### [expense_summary]
Display a quick summary widget of recent expenses.

**Example:**
```
[expense_summary]
```

**Output:**
- Total expenses amount
- Number of recent transactions
- Link to full report

### Creating Pages with Shortcodes

1. Create a new page in WordPress
2. Add the shortcode to the page content:
   ```
   <h2>Track Your Expenses</h2>
   [expense_tracker]
   ```
3. Publish and view

## User Roles & Permissions

### Administrator
- Full access to all features
- Can view all expenses
- Can manage budgets and categories
- Can view reports

### Subscriber/Contributor
- Can view their own expenses
- Can add expenses via frontend shortcode
- Cannot access admin dashboard
- Cannot view other users' expenses

### Guest (Not Logged In)
- Cannot access expense tracker
- See login prompt when accessing pages with shortcodes

## Customization Guide

### Adding Custom Categories

Edit `admin/pages/dashboard.php` and `public/class-public.php` to add default categories:

```php
<select id="expense-category" name="category" required>
    <option value="">-- Select Category --</option>
    <option value="Food">Food</option>
    <option value="YourCategory">Your Category</option>
</select>
```

### Adding Custom Payment Methods

Edit the select fields in dashboard and public pages:

```php
<select id="expense-payment" name="payment_method" required>
    <option value="Cash">Cash</option>
    <option value="YourMethod">Your Method</option>
</select>
```

### Styling Customization

Modify CSS files:
- `assets/css/admin.css` - Admin styling
- `assets/css/public.css` - Frontend styling

### Adding Custom Hooks

In your theme's `functions.php`, add:

```php
// Before adding expense
add_action('expense_tracker_before_add_expense', 'my_custom_function');

function my_custom_function($data) {
    // Your custom code
}
```

## Troubleshooting

### Problem: Tables not created
**Solution:** 
- Deactivate and reactivate the plugin
- Check database user permissions
- Check WordPress error logs

### Problem: AJAX requests failing
**Solution:**
- Verify jQuery is loaded
- Check browser console for errors
- Ensure nonce validation passes
- Clear browser cache

### Problem: Shortcodes not working
**Solution:**
- Verify plugin is activated
- Check page/post content editor
- User must be logged in
- Check for conflicting plugins

### Problem: Missing expenses
**Solution:**
- Check that you're viewing correct user's expenses
- Verify expense date filters
- Check database for data

## Common Tasks

### Bulk Import Expenses
Currently not available. Plan to add CSV import in future version.

### Export Reports
1. Go to **Expenses → Reports**
2. Click **Export as CSV**
3. Data downloads as CSV file

### Delete All Expenses
⚠️ **Use with caution!**
1. Delete expenses individually from dashboard
2. Or remove via database (advanced users only)

### Change Default Currency
Modify budget and display files to add currency symbol:
```php
echo '$' . number_format($amount, 2); // Change $ to your currency
```

## Performance Optimization

### Database Indexes
The plugin automatically creates indexes on:
- `user_id`
- `expense_date`
- `category`

### Query Optimization
Large expense lists load efficiently with:
- Pagination (coming soon)
- Lazy loading (coming soon)
- AJAX-based filtering

## Backup & Restore

### Backup Expenses
1. Export WordPress database
2. Or use WordPress backup plugins
3. Plugin uses standard WordPress tables

### Restore Expenses
1. Restore WordPress database backup
2. Reactivate plugin
3. All data will be available

## Security

The plugin includes:
- ✅ AJAX nonce verification
- ✅ User capability checking
- ✅ Data sanitization
- ✅ SQL injection protection
- ✅ User-specific data isolation

### Best Practices
1. Keep WordPress and plugins updated
2. Use strong user passwords
3. Regularly backup database
4. Monitor user access logs
5. Limit admin access

## Support & Updates

### Getting Help
1. Check README.md for documentation
2. Review this setup guide
3. Check troubleshooting section

### Staying Updated
- Check WordPress admin for plugin updates
- Review changelog before updating
- Backup before updating

## FAQ

**Q: Can multiple users share expenses?**
A: No, currently each user tracks their own expenses. Shared budgets coming soon.

**Q: Is there a mobile app?**
A: No, but the plugin is mobile-responsive and works on mobile browsers.

**Q: Can I export reports?**
A: Yes, use the Export CSV button in Reports section.

**Q: How far back can I track expenses?**
A: No limit - all expenses are stored in database.

**Q: Can I set recurring expenses?**
A: Not in v1.0. Recurring expenses coming in future version.

**Q: Is there multi-currency support?**
A: Not in v1.0. Coming in future update.

---

For more information, visit the plugin documentation or contact support.
