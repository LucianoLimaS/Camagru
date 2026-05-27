<?php
namespace Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Obter variáveis de ambiente configuradas
        $host = getenv('DB_HOST') ?: 'mariadb';
        $db   = getenv('DB_DATABASE') ?: 'camagru';
        $user = getenv('DB_USER') ?: 'camagru_user';
        $pass = getenv('DB_PASSWORD') ?: 'camagru_pass';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
            $this->initializeDatabase($db);
        } catch (PDOException $e) {
            // Instancia o ErrorController e renderiza a view de erro 500 com o layout global
            $controller = new \App\Controllers\ErrorController();
            $controller->show(500, 'Não foi possível conectar ao banco de dados no momento. Por favor, tente novamente mais tarde.');
            exit();
        }
    }

    private function initializeDatabase($dbName) {
        $stmt = $this->connection->prepare("
            SELECT COUNT(*) 
            FROM information_schema.tables 
            WHERE table_schema = :dbName 
              AND table_name = 'users'
        ");
        $stmt->execute(['dbName' => $dbName]);
        $tableExists = (int)$stmt->fetchColumn() > 0;

        if (!$tableExists) {
            $sqlFile = __DIR__ . '/../config/setup.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                $this->connection->exec($sql);
            }
        } else {
            // Check if reset_token column exists, if not, add it
            $stmt = $this->connection->prepare("
                SELECT COUNT(*) 
                FROM information_schema.columns 
                WHERE table_schema = :dbName 
                  AND table_name = 'users' 
                  AND column_name = 'reset_token'
            ");
            $stmt->execute(['dbName' => $dbName]);
            $columnExists = (int)$stmt->fetchColumn() > 0;

            if (!$columnExists) {
                $this->connection->exec("
                    ALTER TABLE `users` 
                    ADD COLUMN `reset_token` VARCHAR(255) DEFAULT NULL,
                    ADD COLUMN `reset_expires` DATETIME DEFAULT NULL
                ");
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
