<?php
require_once __DIR__ . '/../Services/FaqBrain.php';

class FaqController {
  public function page() {
    $view = 'faq';
    include __DIR__ . '/../Views/layout.php';
  }

  public function ask() {
    header('Content-Type: application/json');
    $q = $_POST['q'] ?? '';

    if (!$q) {
      echo json_encode(['error' => 'No question provided']);
      return;
    }

    $brain = new FaqBrain();
    $answer = $brain->respond($q);

    echo json_encode([
      'question' => $q,
      'answer' => $answer,
      'confidence' => $brain->getConfidence()
    ]);
  }
}
