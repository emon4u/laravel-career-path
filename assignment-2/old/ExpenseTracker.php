<?php

class ExpenseTracker {
    private $version        = '1.0.0';
    private $author         = 'Emon Ahmed';
    private $incomeFile     = 'income.json';
    private $expenseFile    = 'expense.json';
    private $categoriesFile = 'categories.json';

    /**
     * Class Constructor
     */
    public function __construct() {
        // Initialize data files if they don't exist
        if (  ! file_exists( $this->incomeFile ) ) {
            file_put_contents( $this->incomeFile, '[]' );
        }
        if (  ! file_exists( $this->expenseFile ) ) {
            file_put_contents( $this->expenseFile, '[]' );
        }
        if (  ! file_exists( $this->categoriesFile ) ) {
            file_put_contents( $this->categoriesFile, '[]' );
        }
    }

    /**
     * Main application loop
     *
     * @return void
     */
    public function run() {
        while ( true ) {
            $this->displayMenu();
            $choice = trim( fgets( STDIN ) );

            switch ( $choice ) {
            case 1:
                $this->addIncome();
                break;
            case 2:
                $this->addExpense();
                break;
            case 3:
                $this->viewIncomes();
                break;
            case 4:
                $this->viewExpenses();
                break;
            case 5:
                $this->viewSavings();
                break;
            case 6:
                $this->viewCategories();
                break;
            case 7:
                $this->viewAppInfo();
                break;
            case 8:
                $this->exitApp();
                break;
            default:
                echo "Invalid option. Please try again.\n";
                break;
            }
        }
    }

    /**
     * Display Main Menu
     *
     * @return void
     */
    private function displayMenu() {
        echo "\n1. Add income\n";
        echo "2. Add expense\n";
        echo "3. View incomes\n";
        echo "4. View expenses\n";
        echo "5. View savings\n";
        echo "6. View categories\n";
        echo "7. View App Info\n";
        echo "8. Exit\n";
        echo "\nEnter your option: ";
    }

    /**
     * Save data to a JSON file
     *
     * @param string $file
     * @param array $data
     *
     * @return void
     */
    private function saveData( $file, $data ) {
        file_put_contents( $file, json_encode( $data, JSON_PRETTY_PRINT ) );
    }

    /**
     * Load data from a JSON file
     *
     * @param string $file
     *
     * @return array
     */
    private function loadData( $file ) {
        // Load data from a JSON file
        $data = file_get_contents( $file );
        return json_decode( $data, true );
    }

    /**
     * Add a new category to the list
     *
     * @param string $category
     *
     * @return void
     */
    private function addCategory( $category ) {
        if ( $category ) {
            $categories   = $this->loadData( $this->categoriesFile );
            $categories[] = ["name" => $category];
            $this->saveData( $this->categoriesFile, $categories );
        }
    }

    /**
     * Add income transaction
     *
     * @return void
     */
    private function addIncome() {
        echo "\nEnter income amount: ";
        $amount = trim( fgets( STDIN ) );

        echo "Enter income category: ";
        $category = trim( fgets( STDIN ) );

        $incomes   = $this->loadData( $this->incomeFile );
        $incomes[] = ["amount" => $amount, "category" => $category];
        $this->saveData( $this->incomeFile, $incomes );

        $this->addCategory( $category );

        echo "\nIncome added successfully.\n";
    }

    /**
     * Add expense transaction
     *
     * @return void
     */
    private function addExpense() {
        echo "\nEnter expense amount: ";
        $amount = trim( fgets( STDIN ) );

        echo "Enter expense category: ";
        $category = trim( fgets( STDIN ) );

        $expenses   = $this->loadData( $this->expenseFile );
        $expenses[] = ["amount" => $amount, "category" => $category];
        $this->saveData( $this->expenseFile, $expenses );

        $this->addCategory( $category );

        echo "\nExpense added successfully.\n";
    }

    /**
     * View income transactions
     *
     * @return void
     */
    private function viewIncomes() {
        $incomes     = $this->loadData( $this->incomeFile );
        $totalIncome = 0;

        if (  ! empty( $incomes ) ) {
            echo "\nIncomes:\n";
            foreach ( $incomes as $income ) {
                echo "Amount: {$income['amount']}, Category: {$income['category']}\n";
                $totalIncome += (float) $income['amount'];
            }

            echo "\nTotal Income: $totalIncome\n";
        } else {
            echo "\nNo incomes recorded.\n";
        }
    }

    /**
     * View expense transactions
     *
     * @return void
     */
    private function viewExpenses() {
        $expenses     = $this->loadData( $this->expenseFile );
        $totalExpense = 0;

        if (  ! empty( $expenses ) ) {
            echo "\nExpenses:\n";
            foreach ( $expenses as $expense ) {
                echo "Amount: {$expense['amount']}, Category: {$expense['category']}\n";
                $totalExpense += (float) $expense['amount'];
            }

            echo "\nTotal Expenses: $totalExpense\n";
        } else {
            echo "\nNo expenses recorded.\n";
        }
    }

    /**
     * View total savings
     *
     * @return void
     */
    private function viewSavings() {
        $incomes  = $this->loadData( $this->incomeFile );
        $expenses = $this->loadData( $this->expenseFile );

        $totalIncome  = 0;
        $totalExpense = 0;

        foreach ( $incomes as $income ) {
            $totalIncome += (float) $income['amount'];
        }

        foreach ( $expenses as $expense ) {
            $totalExpense += (float) $expense['amount'];
        }

        $savings = $totalIncome - $totalExpense;
        echo "\nTotal Savings: $savings\n";
    }

    /**
     * View available categories
     *
     * @return void
     */
    private function viewCategories() {
        $categories = $this->loadData( $this->categoriesFile );
        if (  ! empty( $categories ) ) {
            echo "\nCategories:\n";

            foreach ( $categories as $category ) {
                echo "{$category['name']}\n";
            }
        } else {
            echo "\nNo categories recorded.\n";
        }
    }

    /**
     * View application information
     *
     * @return void
     */
    private function viewAppInfo() {
        echo "\nExpenseTracker - Track your Daily Expenses.\n";
        echo "Version: " . $this->version . "\n";
        echo "Author: " . $this->author . "\n";
    }

    /**
     * Exit the application
     *
     * @return void
     */
    private function exitApp() {
        echo "\nExiting the ExpenseTracker app. Bye!\n";
        exit( 0 );
    }
}

$expenseTracker = new ExpenseTracker();
$expenseTracker->run();
