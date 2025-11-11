<?php

class Policy
{
    /**
     * 🧠 Determine which approvals are needed based on the task payload.
     *
     * Returns an associative array like:
     * [
     *   'manager' => true,
     *   'hr'      => false,
     *   'it'      => true
     * ]
     */
    public static function approvalsNeeded(array $payload): array
    {
        $cost = $payload['hardware_cost'] ?? 0;
        $role = strtolower($payload['role'] ?? '');
        $ctc  = (float)($payload['ctc'] ?? 0);
        $bundle = strtolower($payload['bundle'] ?? '');

        $approvals = [
            'manager' => false,
            'hr'      => false,
            'it'      => false
        ];

        // 🖥️ Hardware approval rule
        if ($cost > 50000 || str_contains($bundle, 'developer')) {
            $approvals['manager'] = true;
            $approvals['it'] = true;
        }

        // 💰 Salary/offer threshold rule
        if ($ctc > 1500000) {
            $approvals['hr'] = true;
            $approvals['manager'] = true;
        }

        // 🧑‍💻 Role-based rule (senior positions need HR approval)
        $seniorKeywords = ['lead', 'manager', 'head', 'director'];
        foreach ($seniorKeywords as $word) {
            if (str_contains($role, $word)) {
                $approvals['hr'] = true;
                break;
            }
        }

        return $approvals;
    }

    /**
     * ✅ Helper: determine if all approvals are granted
     */
    public static function isApproved(array $approvals): bool
    {
        // Returns true only if all required approvals are satisfied
        return !in_array(true, $approvals, true);
    }
}
