<?php
return [
  // Dashboard
  'GET /'                   => ['Dashboard', 'index'],

  // 🔹 Onboarding
  'GET /onboarding'         => ['OnboardingController', 'form'],
  'POST /onboarding/submit' => ['OnboardingController', 'submit'],

  // 🔹 Interview
  'GET /interview'          => ['InterviewController', 'schedule'],
  'POST /interview/submit'  => ['InterviewController', 'submit'],

  // 🔹 Offer
  'GET /offer'              => ['OfferController', 'form'],
  'POST /offer/submit'      => ['OfferController', 'submit'],

  // 🔹 Leave Management (Staff Access)
  'GET /leave'              => ['LeaveController', 'form'],
  'POST /leave/submit'      => ['LeaveController', 'submit'],

  // ❌ Removed /leave/summary for users (Admin will handle it separately)

  // 🔹 HR FAQ
  'GET /faq'                => ['FaqController', 'page'],
  'POST /faq/ask'           => ['FaqController', 'ask'],

  // 🔹 Admin Specific Routes
  'GET /admin'              => ['AdminController', 'login'],
  'POST /admin/login'       => ['AdminController', 'authenticate'],
  'GET /admin/dashboard'    => ['AdminController', 'dashboard'],

  // 🔹 Admin Leave Management
  'POST /leave/approve'     => ['LeaveController', 'approve'],
  'POST /leave/reject'      => ['LeaveController', 'reject'],
];
