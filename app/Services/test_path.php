<?php
$path = dirname(__DIR__) . '/libs/PHPMailer/src/Exception.php';
if (file_exists($path)) {
  echo "✅ File found at: $path";
} else {
  echo "❌ File not found at: $path";
}
