<?php

class Expense_Tracker_Database {

    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Expenses table
        $expenses_table = $wpdb->prefix . 'expenses';
        $expenses_sql = "CREATE TABLE IF NOT EXISTS $expenses_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            category varchar(100) NOT NULL,
            description longtext,
            amount decimal(10,2) NOT NULL,
            expense_date date NOT NULL,
            payment_method varchar(50),
            status varchar(20) DEFAULT 'pending',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY expense_date (expense_date),
            KEY category (category)
        ) $charset_collate;";
        
        // Categories table
        $categories_table = $wpdb->prefix . 'expense_categories';
        $categories_sql = "CREATE TABLE IF NOT EXISTS $categories_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            name varchar(100) NOT NULL,
            description longtext,
            color varchar(7),
            icon varchar(50),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        // Budget table
        $budgets_table = $wpdb->prefix . 'expense_budgets';
        $budgets_sql = "CREATE TABLE IF NOT EXISTS $budgets_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            category varchar(100) NOT NULL,
            amount decimal(10,2) NOT NULL,
            period varchar(20) DEFAULT 'monthly',
            start_date date NOT NULL,
            end_date date,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        dbDelta($expenses_sql);
        dbDelta($categories_sql);
        dbDelta($budgets_sql);
    }
    
    public function get_user_expenses($user_id, $filters = array()) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'expenses';
        $query = $wpdb->prepare("SELECT * FROM $table WHERE user_id = %d", $user_id);
        
        if (!empty($filters['category'])) {
            $query .= $wpdb->prepare(" AND category = %s", $filters['category']);
        }
        
        if (!empty($filters['start_date'])) {
            $query .= $wpdb->prepare(" AND expense_date >= %s", $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $query .= $wpdb->prepare(" AND expense_date <= %s", $filters['end_date']);
        }
        
        if (!empty($filters['status'])) {
            $query .= $wpdb->prepare(" AND status = %s", $filters['status']);
        }
        
        $query .= " ORDER BY expense_date DESC";
        
        if (!empty($filters['limit'])) {
            $query .= $wpdb->prepare(" LIMIT %d", $filters['limit']);
        }
        
        return $wpdb->get_results($query);
    }
    
    public function add_expense($data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'expenses';
        
        $result = $wpdb->insert(
            $table,
            array(
                'user_id' => $data['user_id'],
                'category' => $data['category'],
                'description' => $data['description'],
                'amount' => $data['amount'],
                'expense_date' => $data['expense_date'],
                'payment_method' => $data['payment_method'],
                'status' => $data['status'] ?? 'pending'
            ),
            array('%d', '%s', '%s', '%f', '%s', '%s', '%s')
        );
        
        return $wpdb->insert_id;
    }
    
    public function update_expense($expense_id, $data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'expenses';
        
        return $wpdb->update(
            $table,
            $data,
            array('id' => $expense_id),
            array('%s'),
            array('%d')
        );
    }
    
    public function delete_expense($expense_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'expenses';
        
        return $wpdb->delete($table, array('id' => $expense_id), array('%d'));
    }
}
