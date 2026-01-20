# Contributing to Expense Tracker

Thank you for your interest in contributing to the Expense Tracker WordPress Plugin! This document provides guidelines and instructions for contributing.

## Code of Conduct

Be respectful, inclusive, and constructive in all interactions with other contributors and users.

## How to Contribute

### Reporting Bugs

Before submitting a bug report, please check the [existing issues](../../issues) to avoid duplicates.

When reporting a bug, include:

1. **Clear Title**: Concise description of the bug
2. **WordPress Version**: The version you're running
3. **PHP Version**: Your server's PHP version
4. **Steps to Reproduce**: Detailed steps to reproduce the issue
5. **Expected Behavior**: What should happen
6. **Actual Behavior**: What actually happens
7. **Screenshots**: If applicable
8. **Error Messages**: Any error logs or console messages

**Example:**
```
Title: Budget not saving when end_date is empty

WordPress Version: 6.2
PHP Version: 8.0
Steps to Reproduce:
1. Go to Expenses > Budget
2. Click + Set Budget
3. Fill in all required fields except End Date
4. Click Save

Expected: Budget saves successfully
Actual: Page reloads with no budget saved
```

### Suggesting Enhancements

We welcome feature requests! Please:

1. Check [existing issues](../../issues) for similar suggestions
2. Use a clear, descriptive title
3. Explain the use case and benefits
4. Provide examples or mockups if helpful

### Submitting Pull Requests

1. **Fork the Repository**
   ```bash
   git clone https://github.com/yourusername/expense-tracker-wordpress.git
   cd expense-tracker-wordpress
   ```

2. **Create a Feature Branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```
   
   Use descriptive branch names:
   - `feature/new-feature-name` for new features
   - `fix/bug-description` for bug fixes
   - `docs/documentation-update` for documentation
   - `refactor/code-improvement` for refactoring

3. **Make Your Changes**
   - Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
   - Write clear, descriptive commit messages
   - Keep commits atomic and logical

4. **Test Your Changes**
   - Test in the latest WordPress version
   - Test with different user roles
   - Check JavaScript console for errors
   - Verify responsive design
   - Test in multiple browsers

5. **Commit Your Changes**
   ```bash
   git commit -m "Add descriptive message about changes"
   ```

   Commit message format:
   ```
   [Type] Brief description

   Longer explanation if needed, wrapped at 72 characters.
   
   Fixes #123
   ```

   Types: `feat`, `fix`, `docs`, `style`, `refactor`, `test`, `chore`

6. **Push to Your Fork**
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Open a Pull Request**
   - Clear title and description
   - Reference any related issues
   - Explain the changes and why they're needed
   - Include before/after screenshots if applicable

## Development Guidelines

### Code Style

#### PHP
- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/php/)
- Use meaningful variable names
- Add comments for complex logic
- Use proper indentation (4 spaces)

```php
public function add_expense( $data ) {
    // Validate data
    if ( empty( $data['amount'] ) ) {
        return false;
    }
    
    // Process expense
    return $this->database->insert_expense( $data );
}
```

#### JavaScript
- Follow WordPress JavaScript standards
- Use meaningful variable names
- Add comments for complex logic
- Use 4 spaces for indentation

```javascript
jQuery(document).ready(function($) {
    $('#expense-form').on('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm()) {
            alert('Please fill in all required fields');
            return;
        }
        
        // Submit form
        submitExpense();
    });
});
```

#### CSS
- Use BEM naming convention
- Group related styles
- Use descriptive class names
- Mobile-first approach

```css
/* Expense item component */
.expense-item {
    padding: 15px;
    border: 1px solid #ddd;
}

.expense-item__title {
    font-size: 16px;
    font-weight: bold;
}

.expense-item--highlighted {
    background-color: #f0f0f0;
}
```

### Security Requirements

All contributions must include:

1. **Nonce Verification** for AJAX requests
   ```php
   check_ajax_referer('expense-tracker-nonce', 'nonce');
   ```

2. **Capability Checks**
   ```php
   if (!current_user_can('manage_options')) {
       wp_send_json_error('Unauthorized');
   }
   ```

3. **Input Sanitization**
   ```php
   $amount = floatval($_POST['amount']);
   $name = sanitize_text_field($_POST['name']);
   $description = sanitize_textarea_field($_POST['description']);
   ```

4. **Output Escaping**
   ```php
   echo esc_html($expense->description);
   echo esc_attr($category->color);
   echo wp_kses_post($content);
   ```

5. **Prepared Statements**
   ```php
   $wpdb->prepare(
       "SELECT * FROM $table WHERE id = %d",
       $id
   );
   ```

### Testing Checklist

Before submitting a PR, verify:

- [ ] Code follows WordPress Coding Standards
- [ ] All security measures implemented
- [ ] Works in latest WordPress version
- [ ] No JavaScript console errors
- [ ] Responsive design verified
- [ ] Database queries optimized
- [ ] Comments added where needed
- [ ] No debug code left in
- [ ] All new features documented

## Documentation

When adding features, please update:

1. **README.md** - Feature overview
2. **SETUP_GUIDE.md** - Usage instructions
3. **DEVELOPMENT.md** - Developer documentation
4. **CHANGELOG.md** - What changed
5. **Code Comments** - Inline documentation

## Review Process

1. **Automated Checks**: GitHub Actions will run tests
2. **Code Review**: Maintainers will review your code
3. **Requested Changes**: Address feedback in new commits
4. **Approval**: Once approved, your PR will be merged

## Questions?

- 💬 Open a [GitHub Discussion](../../discussions)
- 📧 Email: your-email@example.com
- 🔗 Check existing [Issues](../../issues)

## Recognition

Contributors are recognized in:
- [CHANGELOG.md](CHANGELOG.md)
- GitHub Contributors page
- Project documentation

Thank you for contributing! 🎉
