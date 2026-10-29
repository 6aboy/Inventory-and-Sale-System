<?php

class Database
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $database;
    private $conn;

    private function loadEnvFile()
    {
        $envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';

        if (!is_readable($envFile)) {
            return;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
            $key = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            if ($key === '') {
                continue;
            }

            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }

    private function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false || $value === null || $value === '') {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? $default;
        }

        return $value;
    }

    public function __construct()
    {
        $this->loadEnvFile();

        $this->host = $this->env('DB_HOST', '127.0.0.1');
        $this->port = $this->env('DB_PORT', '3306');
        $this->username = $this->env('DB_USERNAME', 'root');
        $this->password = $this->env('DB_PASSWORD', '');
        $this->database = $this->env('DB_NAME', 'inventory_and_sale_system');

        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false,
            ];

            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                $options
            );
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Unable to connect to the database. Check your database settings and ensure XAMPP MySQL is running.");
        }
    }

    public function setConnection($conn)
    {
        $this->conn = $conn;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}