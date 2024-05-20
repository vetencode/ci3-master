<?php

define('ROOTPATH', __DIR__ . '/../');

require_once './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOTPATH);
$dotenv->load();

if ($command === 'db:migrate') {
    echo "\n";

    $filename = isset($argv[2]) ? $argv[2] : '';
    if (!$filename) {
        $filename = _as_k("Enter your file sql name:");
    }
    $sqlFile = ROOTPATH . "/db/$filename.sql";
    if (!file_exists($sqlFile)) {
        ec("ERROR: SQL file not found: /db/$filename.sql\n\n", 'danger');
        exit(1);
    }

    $dbHost = $_ENV['DB_HOST'];
    $dbName = $_ENV['DB_DATABASE'];
    $dbUsername = $_ENV['DB_USERNAME'];
    $dbPassword = $_ENV['DB_PASSWORD'];

    try {
        $db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
    } catch (\mysqli_sql_exception $th) {
        ec("ERROR: Connection to DB failed\n", 'danger');
        ec("ERROR: {$th->getMessage()}\n\n", 'danger');
        exit(1);
    }

    // Clear the database (drop all tables)
    $tablesResult = $db->query("SHOW TABLES");
    print_r($tablesResult);
    if ($tablesResult) {
        // Menghitung jumlah tabel
        $numTables = $tablesResult->num_rows;

        // Menghapus setiap tabel
        while ($row = $tablesResult->fetch_assoc()) {
            $tableName = $row['Tables_in_' . $dbName];
            if ($db->query("DROP TABLE IF EXISTS $tableName")) {
                ec("INFO: Table $tableName dropped successfully\n");
            } else {
                ec("ERROR: Dropping $tableName table failed\n", 'danger');
            }
        }
    } else {
        ec("Error retrieving tables: {$db->error}\n\n");
        $db->close();
        exit(1);
    }

    if (intval($numTables)) {
        echo "\n";
    }

    // Import the .sql file
    $sqlCommands = file_get_contents($sqlFile);
    if ($db->multi_query($sqlCommands)) {
        ec("INFO: SQL file imported successfully.\n\n", 'success');
    } else {
        ec("ERROR: Importing SQL file: $db->error \n", 'danger');
    }

    $db->close();
    exit(1);
}
