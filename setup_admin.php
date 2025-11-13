<?php
require_once __DIR__ . '/config/db.php'; // adjust path if inside root
$hash = password_hash('admin123', PASSWORD_DEFAULT);
DB::query("INSERT INTO admins (name, email, password) VALUES (?, ?, ?)", [
  'Administrator', 'admin@talentflow.com', $hash
]);
echo "✅ Admin account created successfully!";
?>
