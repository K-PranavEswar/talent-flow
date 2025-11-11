<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

class HRISService
{
    /**
     * 👤 Create a new worker record in HRIS (used during onboarding)
     */
    public static function createWorker(array $p): string
    {
        [$code, $res] = post_json(MOCK_BASE . '/hris.php', [
            'legalName'     => $p['name'] ?? '',
            'jobTitle'      => $p['role'] ?? '',
            'startDate'     => $p['start_date'] ?? '',
            'location'      => $p['location'] ?? '',
            'managerEmail'  => $p['manager_email'] ?? ''
        ]);

        if ($code !== 201) {
            throw new Exception('HRIS failed to create worker.');
        }

        return $res['workerId'] ?? 'w_' . rand(1000, 9999);
    }

    /**
     * 🧾 Update an existing worker record (for future features)
     */
    public static function updateWorker(string $workerId, array $fields): bool
    {
        [$code, $res] = post_json(MOCK_BASE . '/hris.php?action=update', [
            'workerId' => $workerId,
            'fields'   => $fields
        ]);

        if ($code !== 200) {
            throw new Exception('HRIS update failed.');
        }

        return $res['success'] ?? true;
    }

    /**
     * 📋 Retrieve a worker profile (optional helper)
     */
    public static function getWorker(string $workerId): array
    {
        [$code, $res] = get_json(MOCK_BASE . '/hris.php?workerId=' . urlencode($workerId));

        if ($code !== 200) {
            throw new Exception('Failed to fetch worker record.');
        }

        return $res ?? [];
    }
}
