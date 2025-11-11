<?php
/**
 * TalentFlow AI HR Assistant (with optional IBM watsonx integration)
 * Author: K. Pranav Eswar
 *
 * Features
 * - Session-based memory (keeps last 10 turns)
 * - Optional watsonx.ai call with fallback to local NLP
 * - Confidence scoring + graceful network handling
 */

class FaqBrain
{
    private float $confidence = 0.0;
    private string $memoryKey = 'faq_chat_history';
    private bool $useWatson = false; // flip true when IBM credentials are set

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$this->memoryKey] ??= [];
    }

    public function getConfidence(): float
    {
        return $this->confidence;
    }

    /** Main entry */
    public function respond(string $question): string
    {
        $q = strtolower(trim($question));
        $this->saveToMemory('user', $q);

        // 1️⃣ Try watsonx
        if ($this->useWatson && ($reply = $this->askWatsonx($q))) {
            $this->saveToMemory('ai', $reply);
            return $reply;
        }

        // 2️⃣ Local fallback
        $reply = $this->localNLP($q);
        $this->saveToMemory('ai', $reply);
        return $reply;
    }

    /** Very light local intent engine */
    private function localNLP(string $q): string
    {
        $intents = [
            'leave' => [
                'keywords' => ['leave', 'holiday', 'vacation', 'day off'],
                'response' => 'You can apply for leave through the HR portal. Annual leaves are 18 days per year plus public holidays.'
            ],
            'onboarding' => [
                'keywords' => ['onboard', 'join', 'orientation', 'training'],
                'response' => 'Our onboarding process includes HR paperwork, IT setup, and a 2-day orientation. Your manager will guide you through it.'
            ],
            'salary' => [
                'keywords' => ['salary', 'pay', 'package', 'ctc'],
                'response' => 'Salaries are processed on the last working day of each month. Please ensure your bank details are correct in HRIS.'
            ],
            'benefits' => [
                'keywords' => ['benefit', 'insurance', 'health', 'allowance'],
                'response' => 'We offer comprehensive health insurance, performance bonuses, and reimbursement for approved certifications.'
            ],
            'policy' => [
                'keywords' => ['policy', 'rules', 'dress code', 'work from home', 'remote'],
                'response' => 'You can find all company policies in the employee handbook or the intranet HR Policy section.'
            ],
            'previous' => [
                'keywords' => ['that', 'it', 'those', 'same', 'earlier', 'before'],
                'response' => function () {
                    $last = $this->getLastAIResponse();
                    return $last ? "You were asking earlier about: \"$last\"" : "Could you clarify what you're referring to?";
                }
            ],
            'default' => [
                'response' => 'I’m not sure about that. Please contact HR Support at hr@company.com for detailed assistance.'
            ],
        ];

        foreach ($intents as $intent => $data) {
            if ($intent === 'default') continue;
            foreach ($data['keywords'] as $word) {
                if (str_contains($q, $word)) {
                    $this->confidence = 0.9;
                    return is_callable($data['response'])
                        ? $data['response']()
                        : $data['response'];
                }
            }
        }

        $this->confidence = 0.4;
        return $intents['default']['response'];
    }

    /** IBM watsonx.ai connection (optional) */
    private function askWatsonx(string $question): ?string
    {
        $apiKey = getenv('WATSONX_API_KEY') ?: '';
        if (!$apiKey) return null;

        $model = 'ibm/granite-13b-chat-v2';
        $url   = 'https://api.us-south.ml.cloud.ibm.com/v1/generation/text';

        $context = $this->buildContextString();
        $payload = json_encode([
            'input' => "You are an HR assistant. Context:\n{$context}\n\nUser: {$question}\nAI:",
            'parameters' => ['model' => $model, 'max_new_tokens' => 150, 'temperature' => 0.7]
        ]);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\nAuthorization: Bearer {$apiKey}\r\n",
                'content' => $payload,
                'timeout' => 10
            ]
        ];

        $response = @file_get_contents($url, false, stream_context_create($opts));
        if ($response === false) return null;

        $data = json_decode($response, true);
        $this->confidence = 0.95;
        return $data['results'][0]['generated_text'] ?? null;
    }

    /** Memory helpers */
    private function saveToMemory(string $role, string $text): void
    {
        $_SESSION[$this->memoryKey][] = [
            'role' => $role,
            'text' => $text,
            'time' => date('Y-m-d H:i:s')
        ];
        $_SESSION[$this->memoryKey] = array_slice($_SESSION[$this->memoryKey], -10);
    }

    private function buildContextString(): string
    {
        $lines = [];
        foreach ($_SESSION[$this->memoryKey] as $msg) {
            $lines[] = ucfirst($msg['role']) . ': ' . $msg['text'];
        }
        return implode("\n", $lines);
    }

    private function getLastAIResponse(): ?string
    {
        $history = $_SESSION[$this->memoryKey];
        for ($i = count($history) - 1; $i >= 0; $i--) {
            if ($history[$i]['role'] === 'ai') {
                return $history[$i]['text'];
            }
        }
        return null;
    }
}
