<?php
/**
 * Database.php
 * Connexion à MySQL via PDO.
 * Utilisé par tous les endpoints de l'API.
 */
class Database
{
    private static $instance = null;

    public static function connect()
    {
        if (self::$instance === null) {
            $host = getenv('MYSQLHOST') ?: 'localhost';
            $port = getenv('MYSQLPORT') ?: '3306';
            $dbname = getenv('MYSQL_DATABASE') ?: 'ensa_connect';
            $user = getenv('MYSQLUSER') ?: 'root';
            $pass = getenv('MYSQLPASSWORD') ?: '';

            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $user, $pass, array(
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ));
            } catch (PDOException $e) {
                // Re-throw so the caller can handle it (with JSON if needed)
                throw $e;
            }
        }
        return self::$instance;
    }
}
?>
