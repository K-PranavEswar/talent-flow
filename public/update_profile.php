<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

$adminId = $_SESSION['admin']['id'];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$photo = $_FILES['photo'] ?? null;

try {
  $params = [$name, $email];
  $sql = "UPDATE admins SET name = ?, email = ?";

  // ✅ Handle password update
  if (!empty($password)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql .= ", password = ?";
    $params[] = $hash;
  }

  // ✅ Handle photo upload
  if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/assets/images/';
    $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $fileName = 'admin_' . $adminId . '_' . time() . '.' . $ext;
    move_uploaded_file($photo['tmp_name'], $uploadDir . $fileName);

    $sql .= ", photo = ?";
    $params[] = $fileName;
    $_SESSION['admin']['photo'] = $fileName;
  }

  $sql .= " WHERE id = ?";
  $params[] = $adminId;

  DB::query($sql, $params);

  $_SESSION['admin']['name'] = $name;
  $_SESSION['admin']['email'] = $email;

  $_SESSION['toast'] = ['msg' => 'Profile updated successfully!', 'type' => 'success'];
  header('Location: admin_profile.php');
  exit;
} catch (Exception $e) {
  $_SESSION['toast'] = ['msg' => 'Error: ' . $e->getMessage(), 'type' => 'error'];
  header('Location: admin_profile.php');
  exit;
}
?>
