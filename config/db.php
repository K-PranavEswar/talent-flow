<?php
// ✅ Prevent re-declaration even if included multiple times
if (!class_exists('DB')) {

    class DB {
        private static ?PDO $pdo = null;

        private static function connect(): PDO {
            if (self::$pdo === null) {
                $host = 'localhost';
                $db   = 'talentflow';
                $user = 'root';
                $pass = '';
                $charset = 'utf8mb4';
                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

                try {
                    self::$pdo = new PDO($dsn, $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                } catch (PDOException $e) {
                    die("❌ Database connection failed: " . $e->getMessage());
                }
            }
            return self::$pdo;
        }

        public static function query(string $sql, array $params = []): bool {
            $stmt = self::connect()->prepare($sql);
            return $stmt->execute($params);
        }

        public static function fetch(string $sql, array $params = []): ?array {
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ?: null;
        }

        public static function fetchAll(string $sql, array $params = []): array {
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }
    }
}
?>
