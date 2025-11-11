<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

class ITSMService
{
    /**
     * 🧾 Create a new access + hardware ticket for a worker
     * Used in onboarding orchestration.
     */
    public static function createAccessTicket(string $workerId, array $p): string
    {
        [$code, $res] = post_json(MOCK_BASE . '/itsm.php', [
            'type'      => 'Access+Hardware',
            'workerId'  => $workerId,
            'bundle'    => $p['bundle'] ?? 'DataAnalyst',
            'requestedBy' => $p['manager_email'] ?? 'hr@company.com',
            'priority'  => 'High',
            'description' => 'Provision access and equipment for new hire: ' . ($p['name'] ?? 'Unknown')
        ]);

        if ($code !== 201) {
            throw new Exception('ITSM failed to create ticket.');
        }

        return $res['ticketId'] ?? 'TCK-' . rand(1000, 9999);
    }

    /**
     * 🔄 Update the status or details of an existing ITSM ticket.
     */
    public static function updateTicket(string $ticketId, array $fields): bool
    {
        [$code, $res] = post_json(MOCK_BASE . '/itsm.php?action=update', [
            'ticketId' => $ticketId,
            'fields'   => $fields
        ]);

        if ($code !== 200) {
            throw new Exception("ITSM update failed for ticket $ticketId.");
        }

        return $res['success'] ?? true;
    }

    /**
     * 📋 Retrieve details or status of a ticket.
     */
    public static function getTicket(string $ticketId): array
    {
        [$code, $res] = get_json(MOCK_BASE . '/itsm.php?ticketId=' . urlencode($ticketId));

        if ($code !== 200) {
            throw new Exception("Failed to fetch ITSM ticket $ticketId.");
        }

        return $res ?? [];
    }

    /**
     * ✅ Close or resolve an ITSM ticket.
     */
    public static function closeTicket(string $ticketId): bool
    {
        [$code, $res] = post_json(MOCK_BASE . '/itsm.php?action=close', [
            'ticketId' => $ticketId
        ]);

        if ($code !== 200) {
            throw new Exception("Failed to close ITSM ticket $ticketId.");
        }

        return $res['success'] ?? true;
    }
}
