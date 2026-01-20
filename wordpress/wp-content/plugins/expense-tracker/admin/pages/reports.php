<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$database = new Expense_Tracker_Database();
$current_user_id = get_current_user_id();
$expenses = $database->get_user_expenses($current_user_id);

// Group expenses by category for charts
$by_category = array();
foreach ($expenses as $expense) {
    if (!isset($by_category[$expense->category])) {
        $by_category[$expense->category] = 0;
    }
    $by_category[$expense->category] += $expense->amount;
}
?>

<div class="wrap">
    <h1><?php _e('Expense Reports', 'expense-tracker'); ?></h1>
    
    <div class="reports-section">
        
        <!-- Filters -->
        <div class="report-filters">
            <input type="date" id="filter-start-date" placeholder="Start Date">
            <input type="date" id="filter-end-date" placeholder="End Date">
            <select id="filter-category">
                <option value="">All Categories</option>
                <option value="Food">Food</option>
                <option value="Transportation">Transportation</option>
                <option value="Utilities">Utilities</option>
                <option value="Entertainment">Entertainment</option>
                <option value="Health">Health</option>
                <option value="Shopping">Shopping</option>
                <option value="Other">Other</option>
            </select>
            <button class="button" id="apply-filters"><?php _e('Apply Filters', 'expense-tracker'); ?></button>
            <button class="button" id="export-report"><?php _e('Export as CSV', 'expense-tracker'); ?></button>
        </div>
        
        <!-- Charts -->
        <div class="charts-container">
            <div class="chart-wrapper">
                <h3><?php _e('Expenses by Category', 'expense-tracker'); ?></h3>
                <canvas id="categoryChart"></canvas>
            </div>
            
            <div class="chart-wrapper">
                <h3><?php _e('Spending Trends', 'expense-tracker'); ?></h3>
                <canvas id="trendsChart"></canvas>
            </div>
        </div>
        
        <!-- Summary Statistics -->
        <div class="report-summary">
            <div class="stat-box">
                <h4><?php _e('Total Expenses', 'expense-tracker'); ?></h4>
                <p class="stat-value">$<?php echo number_format(array_sum($by_category), 2); ?></p>
            </div>
            <div class="stat-box">
                <h4><?php _e('Average Expense', 'expense-tracker'); ?></h4>
                <p class="stat-value">$<?php echo number_format(count($expenses) > 0 ? array_sum($by_category) / count($expenses) : 0, 2); ?></p>
            </div>
            <div class="stat-box">
                <h4><?php _e('Total Records', 'expense-tracker'); ?></h4>
                <p class="stat-value"><?php echo count($expenses); ?></p>
            </div>
        </div>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample data for charts
    var ctx = document.getElementById('categoryChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_keys($by_category)); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_values($by_category)); ?>,
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
