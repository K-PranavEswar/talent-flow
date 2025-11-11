<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

class IAMService
{
    /**
     * 👤 Create a new user account in the Identity Access Management system
     */
    public static function createUser(string $name, ?string $email, array $groups = []): string
    {
        // Prepare payload
        $payload = [
            'name'   => $name,
            'email'  => $email ?? self::generateEmail($name),
            'groups' => $groups
        ];

        // Send mock API request
        [$code, $res] = post_json(MOCK_BASE . '/iam.php', $payload);

        if ($code !== 201) {
            throw new Exception('IAM service failed to create user.');
        }

        return $res['userId'] ?? 'u_' . rand(1000, 9999);
    }

    /**
     * 🔒 Disable or deactivate a user account
     */
    public static function disableUser(string $userId): bool
    {
        [$code, $res] = post_json(MOCK_BASE . '/iam.php?action=disable', [
            'userId' => $userId
        ]);

        if ($code !== 200) {
            throw new Exception("IAM service failed to disable user $userId.");
        }

        return $res['success'] ?? true;
    }

    /**
     * 📋 Retrieve IAM user info
     */
    public static function getUser(string $userId): array
    {
        [$code, $res] = get_json(MOCK_BASE . '/iam.php?userId=' . urlencode($userId));

        if ($code !== 200) {
            throw new Exception("Failed to fetch IAM user details for $userId.");
        }

        return $res ?? [];
    }

    /**
     * ✉️ Generate a fallback email if not provided
     */
    private static function generateEmail(string $name): string
    {
        $slug = strtolower(str_replace(' ', '.', trim($name)));
        return $slug . '@company.com';
    }
}
