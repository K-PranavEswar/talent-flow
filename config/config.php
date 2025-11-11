<?php
// Prevent redeclaration if config.php is required multiple times
if (!function_exists('env')) {
  function env($key, $default = null) {
    static $env;
    if (!$env) {
      $env = [];
      $path = dirname(__DIR__) . '/.env';
      if (file_exists($path)) {
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
          if (str_starts_with(trim($line), '#')) continue;
          [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
          $env[trim($k)] = trim($v);
        }
      }
    }
    return $env[$key] ?? $default;
  }
}

// Define app-wide constants
if (!defined('APP_URL')) {
  define('APP_URL', env('APP_URL', 'http://localhost/talentflow/public'));
}
if (!defined('MOCK_BASE')) {
  define('MOCK_BASE', env('MOCK_BASE', 'http://localhost/talentflow/mock'));
}

// Return database configuration
return [
  'db' => [
    'host' => env('DB_HOST', '127.0.0.1'),
    'name' => env('DB_NAME', 'talentflow'),
    'user' => env('DB_USER', 'root'),
    'pass' => env('DB_PASS', ''),
  ]
];
