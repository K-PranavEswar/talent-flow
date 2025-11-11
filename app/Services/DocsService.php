<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers.php';

class DocsService {

  /**
   * 📘 Generate a Welcome Pack document (for onboarding)
   */
  public static function generateWelcomePack(array $p): string {
    [$code, $res] = post_json(MOCK_BASE . '/docs.php', [
      'template' => 'WelcomePack',
      'data' => $p
    ]);

    if ($code !== 201) {
      throw new Exception('Failed to generate Welcome Pack document.');
    }

    return $res['link'] ?? ('https://docs.local/welcome/' . urlencode($p['name']));
  }

  /**
   * 📄 Generate an Offer Letter document (for offers)
   */
  public static function generateOfferLetter(array $p): string {
    [$code, $res] = post_json(MOCK_BASE . '/docs.php', [
      'template' => 'OfferLetter',
      'data' => [
        'candidate'  => $p['candidate'] ?? '',
        'role'       => $p['role'] ?? '',
        'ctc'        => $p['ctc'] ?? '',
        'start_date' => $p['start_date'] ?? '',
        'issued_by'  => 'HR Department'
      ]
    ]);

    if ($code !== 201) {
      throw new Exception('Failed to generate Offer Letter.');
    }

    return $res['link'] ?? ('https://docs.local/offers/' . urlencode($p['candidate']));
  }
}
