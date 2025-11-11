<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

class MessagingService
{
    /**
     * ✅ Notify the manager after successful onboarding
     */
    public static function notifyManager(string $to, string $name, array $ids): void
    {
        $body = "✅ Onboarding completed for *{$name}*.\n\n"
              . "Details:\n" . json_encode($ids, JSON_PRETTY_PRINT);

        [$code, $res] = post_json(MOCK_BASE . '/messaging.php', [
            'channel' => 'email',
            'to'      => [$to],
            'subject' => "Onboarding Summary for {$name}",
            'body'    => $body
        ]);

        if ($code !== 200) {
            throw new Exception('Messaging failed to notify manager.');
        }
    }

    /**
     * 📅 Notify interview panel members about scheduled interviews
     */
    public static function notifyInterviewPanel(string $panel, array $p): void
    {
        $panelEmails = array_map('trim', explode(',', $panel));
        $candidate = $p['candidate'] ?? 'Unknown';
        $role      = $p['role'] ?? 'N/A';
        $date      = $p['date'] ?? 'TBD';

        $body = "📅 Interview scheduled for *{$candidate}*\n"
              . "Role: {$role}\n"
              . "Date: {$date}\n"
              . "Please check your calendar for meeting details.";

        [$code, $res] = post_json(MOCK_BASE . '/messaging.php', [
            'channel' => 'email',
            'to'      => $panelEmails,
            'subject' => "Interview scheduled: {$candidate} ({$role})",
            'body'    => $body
        ]);

        if ($code !== 200) {
            throw new Exception('Failed to notify interview panel.');
        }
    }

    /**
     * ✉️ Send offer letter notification to candidate
     */
    public static function sendOffer(string $candidate, string $role, string $offerLink): void
    {
        $email = self::generateEmail($candidate);

        $body = "🎉 Congratulations {$candidate}!\n\n"
              . "We’re excited to offer you the position of *{$role}*.\n"
              . "Please review your offer letter here:\n{$offerLink}\n\n"
              . "Kind regards,\nTalentFlow HR";

        [$code, $res] = post_json(MOCK_BASE . '/messaging.php', [
            'channel' => 'email',
            'to'      => [$email],
            'subject' => "Your Offer Letter — {$role}",
            'body'    => $body
        ]);

        if ($code !== 200) {
            throw new Exception("Failed to send offer letter to {$candidate}.");
        }
    }

    /**
     * 💬 General helper to send messages to any recipient
     */
    public static function sendMessage(array $to, string $subject, string $body): bool
    {
        [$code, $res] = post_json(MOCK_BASE . '/messaging.php', [
            'channel' => 'email',
            'to'      => $to,
            'subject' => $subject,
            'body'    => $body
        ]);

        if ($code !== 200) {
            throw new Exception('General message sending failed.');
        }

        return $res['success'] ?? true;
    }

    /**
     * 🧠 Helper to auto-generate candidate email from name (mock)
     */
    private static function generateEmail(string $name): string
    {
        $slug = strtolower(str_replace(' ', '.', trim($name)));
        return $slug . '@example.com';
    }
}
