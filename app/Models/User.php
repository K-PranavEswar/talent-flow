<?php
require_once __DIR__ . '/DB.php';

class User {

    public static function all(): array {
        $pdo = DB::pdo();
        $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByEmail(string $email): ?array {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function findById(int $id): ?array {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function delete(int $id): bool {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function updatePhoto(int $id, string $filename): bool {
        $pdo = DB::pdo();
        $stmt = $pdo->prepare("UPDATE users SET photo = ? WHERE id = ?");
        return $stmt->execute([$filename, $id]);
    }
}
?>
