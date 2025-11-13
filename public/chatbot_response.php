<?php
/**
 * TalentFlow AI HR Chatbot Response Engine
 * Author: K. Pranav Eswar (MACSEEDS)
 * Description: Processes user queries via an intent-based HR knowledge base.
 */

header('Content-Type: application/json');

// ---------------- Helper Functions ----------------

function normalizeText(string $text): string {
    $text = strtolower($text);
    $text = preg_replace('/[?!.,;\'"]/', '', $text);
    return trim($text);
}

function getBestMatch(string $normalizedQuestion, array $knowledgeBase): string {
    $bestScore = 0;
    $bestResponse = "I'm sorry, I don't have information on that topic right now. Please try rephrasing, or contact an HR representative for help.";
    $fallbackResponse = "I'm here to assist with HR queries! How can I help?";
    $greetingResponse = "Hey there 👋! How can I help you with HR-related info today?";

    if (empty($normalizedQuestion)) return $fallbackResponse;

    $questionWords = explode(' ', $normalizedQuestion);

    foreach ($knowledgeBase as $intent) {
        $currentScore = 0;
        $category = $intent['category'] ?? 'general';

        foreach ($intent['keywords'] as $keyword) {
            $normalizedKeyword = normalizeText($keyword);

            if (str_contains($normalizedQuestion, $normalizedKeyword)) $currentScore += 10;

            foreach ($questionWords as $word) {
                if (strlen($word) > 2 && $word === $normalizedKeyword) $currentScore += 5;
            }
        }

        if (isset($intent['priority']) && $intent['priority']) {
            foreach ($intent['keywords'] as $keyword) {
                if (str_contains($normalizedQuestion, normalizeText($keyword))) $currentScore += 20;
            }
        }

        if ($currentScore > $bestScore) {
            $bestScore = $currentScore;
            $bestResponse = $intent['response'];
            if ($category === 'greetings' && $bestScore > 0) $greetingResponse = $intent['response'];
        }
    }

    if ($bestScore < 10) {
        if (str_contains($normalizedQuestion, 'hello') || str_contains($normalizedQuestion, 'hi') || str_contains($normalizedQuestion, 'hey'))
            return $greetingResponse;
        return $fallbackResponse;
    }

    return $bestResponse;
}

// ---------------- Main Logic ----------------

$data = json_decode(file_get_contents('php://input'), true);
$question = $data['question'] ?? '';
$normalizedQuestion = normalizeText($question);

// ---------------- Full Knowledge Base ----------------

$knowledgeBase = [

    // Greetings
    [
        'category' => 'greetings',
        'keywords' => ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'greetings', 'namaste'],
        'response' => "Hey there 👋! How can I help you with HR-related info today?"
    ],
    [
        'category' => 'greetings',
        'keywords' => ['how are you', 'how is it going'],
        'response' => "I'm great! Always ready to help with HR and TalentFlow queries. How can I assist?"
    ],
    [
        'category' => 'greetings',
        'keywords' => ['thanks', 'thank you', 'appreciate'],
        'response' => "You're most welcome! 😊"
    ],
    [
        'category' => 'greetings',
        'keywords' => ['bye', 'goodbye', 'see you'],
        'response' => "Goodbye! Have a productive day 💼"
    ],

    // Leave
    [
        'category' => 'leave',
        'keywords' => ['leave policy', 'how many leaves', 'paid leave', 'vacation'],
        'response' => "Employees are entitled to 12 annual paid leaves per year, excluding national holidays."
    ],
    [
        'category' => 'leave',
        'keywords' => ['sick leave', 'medical leave', 'not feeling well'],
        'response' => "Employees get 6 paid sick leaves annually. For 3+ days, please provide a medical certificate."
    ],
    [
        'category' => 'leave',
        'keywords' => ['casual leave', 'cl', 'short leave'],
        'response' => "You can take up to 6 casual leaves per year for short-term or unplanned needs."
    ],
    [
        'category' => 'leave',
        'keywords' => ['unpaid leave', 'loss of pay', 'lwop'],
        'response' => "Unpaid leave can be taken after manager approval when paid leaves are exhausted."
    ],
    [
        'category' => 'leave',
        'keywords' => ['half day', 'halfday', 'short day'],
        'response' => "Yes, you can request half-day leave through the Leave Management tab."
    ],

    // Remote work
    [
        'category' => 'remote',
        'keywords' => ['remote', 'work from home', 'wfh policy', 'hybrid work'],
        'response' => "We follow a flexible hybrid work model. You can apply for remote days in the Leave Management tab."
    ],
    [
        'category' => 'remote',
        'keywords' => ['work from another city', 'workation', 'out of station'],
        'response' => "Working from another city is allowed only after HR and manager approval for compliance reasons."
    ],

    // Offer
    [
        'category' => 'offer',
        'keywords' => ['offer letter', 'job offer', 'when will i get offer'],
        'response' => "Offer letters are typically shared within 3–5 business days after HR approval."
    ],
    [
        'category' => 'offer',
        'keywords' => ['sign offer', 'accept offer', 'offer confirmation'],
        'response' => "You can review and accept your offer digitally through the link sent by HR."
    ],
    [
        'category' => 'offer',
        'keywords' => ['background verification', 'bgv', 'verification'],
        'response' => "Background verification is mandatory for all new hires. Please submit required documents promptly."
    ],

    // IT / Password
    [
        'category' => 'it',
        'keywords' => ['password', 'reset', 'forgot password', 'change password'],
        'response' => "Go to the TalentFlow login page and click 'Forgot Password' to reset your credentials.",
        'priority' => true
    ],
    [
        'category' => 'it',
        'keywords' => ['email not working', 'vpn issue', 'system issue'],
        'response' => "Please raise a ticket with IT Helpdesk at helpdesk@company.com or call extension x1234."
    ],

    // Salary & Payroll
    [
        'category' => 'salary',
        'keywords' => ['salary', 'ctc', 'compensation', 'payslip'],
        'response' => "Salary is credited on the last working day of the month. Payslips are available in the Payroll Portal."
    ],
    [
        'category' => 'salary',
        'keywords' => ['bonus', 'incentive', 'performance bonus'],
        'response' => "Bonuses depend on both individual and company performance. They're usually paid annually."
    ],
    [
        'category' => 'salary',
        'keywords' => ['tax', 'form 16', 'tds'],
        'response' => "Form 16 and tax details are shared every financial year via the Payroll Portal."
    ],

    // Benefits
    [
        'category' => 'benefits',
        'keywords' => ['insurance', 'health insurance', 'medical coverage'],
        'response' => "All employees are covered under our group health insurance. Dependents can be added during onboarding or renewal."
    ],
    [
        'category' => 'benefits',
        'keywords' => ['gratuity', 'pf', 'pension'],
        'response' => "Gratuity is applicable after 5 years of continuous service. PF contributions are visible on the EPFO portal."
    ],
    [
        'category' => 'benefits',
        'keywords' => ['eap', 'mental health', 'counseling'],
        'response' => "We provide confidential counseling through our Employee Assistance Program (EAP). Details on the HR portal."
    ],

    // Onboarding
    [
        'category' => 'onboarding',
        'keywords' => ['onboarding', 'first day', 'joining', 'orientation'],
        'response' => "Welcome aboard! Your first day includes orientation, IT setup, and introductions with your manager."
    ],
    [
        'category' => 'onboarding',
        'keywords' => ['documents', 'joining documents', 'what to bring'],
        'response' => "Please bring your ID proof and educational certificates for verification on Day 1."
    ],

    // Offboarding
    [
        'category' => 'offboarding',
        'keywords' => ['resignation', 'notice period', 'quit', 'exit'],
        'response' => "The standard notice period is 60 days. Submit your resignation in writing to your manager and HR."
    ],
    [
        'category' => 'offboarding',
        'keywords' => ['experience letter', 'relieving letter'],
        'response' => "Experience and relieving letters are shared with your Full & Final settlement after clearance."
    ],

    // Policy
    [
        'category' => 'policy',
        'keywords' => ['policy', 'code of conduct', 'posh'],
        'response' => "All company policies are available in the Employee Handbook on the HR Portal."
    ],
    [
        'category' => 'policy',
        'keywords' => ['dress code', 'work hours', 'timings'],
        'response' => "Standard hours are 9 AM–6 PM, Mon–Fri. Dress code is business casual (smart casuals on Fridays)."
    ],

    // Performance
    [
        'category' => 'performance',
        'keywords' => ['review', 'appraisal', 'promotion'],
        'response' => "Performance reviews happen annually in March-April. Promotions and raises are performance-based."
    ],

    // Referrals
    [
        'category' => 'referral',
        'keywords' => ['referral', 'refer friend', 'referral bonus'],
        'response' => "Refer candidates via the Careers Portal. Bonuses are paid after the candidate completes 3 months."
    ],

    // Facilities
    [
        'category' => 'facilities',
        'keywords' => ['parking', 'cafeteria', 'id card', 'access card'],
        'response' => "Parking and cafeteria facilities are available onsite. Report lost ID cards to admin immediately."
    ],

    // Diversity
    [
        'category' => 'diversity',
        'keywords' => ['diversity', 'inclusion', 'erg', 'women in tech'],
        'response' => "We’re committed to Diversity & Inclusion. Join our ERGs like Pride Network and Women in Tech!"
    ],

    // Training
    [
        'category' => 'training',
        'keywords' => ['training', 'lms', 'course', 'certification'],
        'response' => "Access internal courses via the Learning Management System. Certification reimbursements are available."
    ],

    // Culture
    [
        'category' => 'culture',
        'keywords' => ['events', 'townhall', 'team outing', 'awards'],
        'response' => "We host quarterly townhalls, team outings, and recognition programs like ‘Star of the Month’. Stay tuned!"
    ]
];

// ---------------- Output ----------------

$answer = getBestMatch($normalizedQuestion, $knowledgeBase);
echo json_encode(['answer' => $answer]);
