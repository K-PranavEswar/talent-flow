<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

class CalendarService {

  /**
   * 📅 Schedule employee orientation for onboarding
   */
  public static function scheduleOrientation(array $p): string {
    [$code, $res] = post_json(MOCK_BASE . '/calendar.php', [
      'summary'   => 'Orientation for ' . $p['name'],
      'start'     => $p['start_date'] . 'T10:00:00',
      'end'       => $p['start_date'] . 'T11:00:00',
      'attendees' => [$p['manager_email'], 'hr@company.com'],
      'location'  => $p['location']
    ]);

    if ($code !== 201) {
      throw new Exception('Calendar scheduling failed for Orientation.');
    }

    return $res['eventId'] ?? ('ORNT-' . rand(1000, 9999));
  }

  /**
   * 🗓️ Schedule an interview event
   */
  public static function scheduleInterview(array $p): string {
    [$code, $res] = post_json(MOCK_BASE . '/calendar.php', [
      'summary'   => 'Interview with ' . ($p['candidate'] ?? 'Unknown Candidate'),
      'start'     => ($p['date'] ?? date('Y-m-d')) . 'T14:00:00',
      'end'       => ($p['date'] ?? date('Y-m-d')) . 'T15:00:00',
      'attendees' => array_merge(
        [$p['candidate'] ?? 'unknown@company.com'],
        isset($p['panel']) ? explode(',', $p['panel']) : ['hr@company.com']
      ),
      'location'  => $p['location'] ?? 'Meeting Room A'
    ]);

    if ($code !== 201) {
      throw new Exception('Calendar scheduling failed for Interview.');
    }

    return $res['eventId'] ?? ('INTV-' . rand(1000, 9999));
  }
}
