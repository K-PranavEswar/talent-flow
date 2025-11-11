<?php
// ✅ FIXED: Proper relative path to the DB file
require_once __DIR__ . '/../../config/db.php';

class Admin
{
    // 🧩 Get all admins (for listing or management)
    public static function all(): array
    {
        return DB::fetchAll("SELECT * FROM admins ORDER BY id DESC");
    }

    // 🔍 Find admin by ID
    public static function find(int $id): ?array
    {
        return DB::fetch("SELECT * FROM admins WHERE id = ?", [$id]);
    }

    // 🔍 Find admin by email (used in login)
    public static function findByEmail(string $email): ?array
    {
        return DB::fetch("SELECT * FROM admins WHERE email = ?", [$email]);
    }

    // 🔐 Verify admin login credentials
    public static function verify(string $email, string $password): false|array
    {
        $admin = self::findByEmail($email);
        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }
        return false;
    }

    // ✏️ Update admin profile (name, email, optional password + photo)
    public static function updateProfile(int $id, string $name, string $email, ?string $password = null, ?string $photo = null): bool
    {
        $query = "UPDATE admins SET name = ?, email = ?";
        $params = [$name, $email];

        if (!empty($password)) {
            $query .= ", password = ?";
            $params[] = password_hash($password, PASSWORD_BCRYPT);
        }

        if (!empty($photo)) {
            $query .= ", photo = ?";
            $params[] = $photo;
        }

        $query .= " WHERE id = ?";
        $params[] = $id;

        DB::query($query, $params);
        return true;
    }

    // ➕ Create new admin
    public static function create(string $name, string $email, string $password): bool
    {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        DB::query("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)", [$name, $email, $hashed]);
        return true;
    }

    // ❌ Delete admin
    public static function delete(int $id): bool
    {
        DB::query("DELETE FROM admins WHERE id = ?", [$id]);
        return true;
    }

    // 📸 Update only photo
    public static function updatePhoto(int $id, string $photo): bool
    {
        DB::query("UPDATE admins SET photo = ? WHERE id = ?", [$photo, $id]);
        return true;
    }

    // 🔁 Change password securely
    public static function changePassword(int $id, string $newPassword): bool
    {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        DB::query("UPDATE admins SET password = ? WHERE id = ?", [$hashed, $id]);
        return true;
    }
}
?>
