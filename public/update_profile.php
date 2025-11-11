<?php
session_start();
require_once __DIR__ . '/../app/Models/Admin.php';

$id = $_SESSION['admin']['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
$photo = null;

if (!empty($_FILES['photo']['name'])) {
  $filename = time() . '_' . basename($_FILES['photo']['name']);
  $targetPath = __DIR__ . '/uploads/' . $filename;
  move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath);
  $photo = $filename;
}

Admin::updateProfile($id, $name, $email, $password, $photo);
$_SESSION['toast'] = ['msg' => 'Profile updated successfully!', 'type' => 'success'];
header("Location: admin_profile.php");
exit;
?>
