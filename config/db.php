<?php
// ✅ Prevent re-declaration even if included multiple times
if (!class_exists('DB')) {

    class DB {
        private static ?PDO $pdo = null;

        // ✅ Private connection initializer
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
                        PDO::ATTR_EMULATE_PREPARES => false, // ✅ safer native prepares
                    ]);
                } catch (PDOException $e) {
                    die("❌ Database connection failed: " . htmlspecialchars($e->getMessage()));
                }
            }
            return self::$pdo;
        }

        // ✅ Run INSERT/UPDATE/DELETE (returns affected rows)
        public static function query(string $sql, array $params = []): int {
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        }

        // ✅ Fetch a single row
        public static function fetch(string $sql, array $params = []): ?array {
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ?: null;
        }

        // ✅ Fetch all rows
        public static function fetchAll(string $sql, array $params = []): array {
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }

        // ✅ Optional helper: last inserted ID
        public static function lastInsertId(): string {
            return self::connect()->lastInsertId();
        }

        // ✅ Optional helper: direct access to PDO
        public static function pdo(): PDO {
            return self::connect();
        }
    }
}
?>
