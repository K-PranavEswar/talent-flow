<?php
require_once __DIR__ . '/DB.php';

class User {

  // Find a user by email address
  public static function findByEmail(string $email): ?array {
    $pdo = DB::pdo();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    return $user ?: null;
  }
}
