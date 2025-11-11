<?php

// ✅ Safe view renderer
if (!function_exists('view')) {
    function view(string $name, array $data = []): void {
        extract($data);
        $view = $name;
        include __DIR__ . "/Views/layout.php";
    }
}

// ✅ Safe JSON response helper
if (!function_exists('json_response')) {
    function json_response($data, int $status = 200): void {
        if (headers_sent()) {
            echo json_encode($data);
            exit;
        }
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }
}

// ✅ Safe POST request with JSON payload
if (!function_exists('post_json')) {
    function post_json(string $url, array $payload): array {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 15,
        ]);

        $res = curl_exec($ch);
        if ($res === false) {
            throw new Exception('HTTP Error: ' . curl_error($ch));
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($res, true);
        return [$code, $decoded];
    }
}

// ✅ Safe session starter to prevent “session already active” warnings
if (!function_exists('start_secure_session')) {
    function start_secure_session(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
