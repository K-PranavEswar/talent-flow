<?php
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Models/Run.php';
require_once __DIR__ . '/../Models/Artifact.php';
require_once __DIR__ . '/Policy.php';
require_once __DIR__ . '/HRISService.php';
require_once __DIR__ . '/ITSMService.php';
require_once __DIR__ . '/IAMService.php';
require_once __DIR__ . '/CalendarService.php';
require_once __DIR__ . '/MessagingService.php';
require_once __DIR__ . '/DocsService.php';

class Orchestrator {

  /**
   * 🟢 Full onboarding process automation
   */
  public static function runOnboarding(int $taskId, array $payload): void {
    Task::setStatus($taskId, 'running');
    try {
      // Step 0: Validation
      foreach (['name', 'role', 'start_date', 'manager_email', 'location'] as $f) {
        if (empty($payload[$f])) throw new Exception("Missing field: $f");
      }

      // Step 1: HRIS
      $workerId = HRISService::createWorker($payload);
      RunLog::add($taskId, 'HRIS.createWorker', 'ok', 'Worker created', ['workerId' => $workerId]);
      Artifact::add($taskId, 'workerId', $workerId);

      // Step 2: ITSM
      $ticketId = ITSMService::createAccessTicket($workerId, $payload);
      RunLog::add($taskId, 'ITSM.createTicket', 'ok', 'Access ticket created', ['ticketId' => $ticketId]);
      Artifact::add($taskId, 'ticketId', $ticketId);

      // Step 3: IAM
      $userId = IAMService::createUser($payload['name'], $payload['email'] ?? null, ['GSuite', 'DataTeam']);
      RunLog::add($taskId, 'IAM.createUser', 'ok', 'User created in IAM', ['userId' => $userId]);
      Artifact::add($taskId, 'userId', $userId);

      // Step 4: Calendar
      $eventId = CalendarService::scheduleOrientation($payload);
      RunLog::add($taskId, 'Calendar.createEvent', 'ok', 'Orientation event scheduled', ['eventId' => $eventId]);
      Artifact::add($taskId, 'eventId', $eventId);

      // Step 5: Docs + Notify
      $docLink = DocsService::generateWelcomePack($payload);
      Artifact::add($taskId, 'docLink', $docLink);

      MessagingService::notifyManager(
        $payload['manager_email'],
        $payload['name'],
        compact('workerId', 'ticketId', 'userId', 'eventId', 'docLink')
      );

      RunLog::add($taskId, 'Messaging.send', 'ok', 'Manager notified about onboarding completion');
      Task::setStatus($taskId, 'done');
    } catch (Exception $e) {
      RunLog::add($taskId, 'error', 'failed', $e->getMessage());
      Task::setStatus($taskId, 'error');
    }
  }

  /**
   * 🔵 Interview orchestration workflow
   */
  public static function runInterview(int $taskId, array $payload): void {
    Task::setStatus($taskId, 'running');
    try {
      foreach (['candidate', 'role', 'date'] as $f) {
        if (empty($payload[$f])) throw new Exception("Missing field: $f");
      }

      // Step 1: Schedule interview in calendar
      $eventId = CalendarService::scheduleInterview($payload);
      RunLog::add($taskId, 'Calendar.scheduleInterview', 'ok', 'Interview scheduled', ['eventId' => $eventId]);
      Artifact::add($taskId, 'eventId', $eventId);

      // Step 2: Notify interview panel
      MessagingService::notifyInterviewPanel($payload['panel'], $payload);
      RunLog::add($taskId, 'Messaging.notifyPanel', 'ok', 'Interview panel notified');

      Task::setStatus($taskId, 'done');
    } catch (Exception $e) {
      RunLog::add($taskId, 'error', 'failed', $e->getMessage());
      Task::setStatus($taskId, 'error');
    }
  }

  /**
   * 🟣 Offer orchestration workflow
   */
  public static function runOffer(int $taskId, array $payload): void {
    Task::setStatus($taskId, 'running');
    try {
      foreach (['candidate', 'role', 'ctc', 'start_date'] as $f) {
        if (empty($payload[$f])) throw new Exception("Missing field: $f");
      }

      // Step 1: Generate offer document
      $offerDoc = DocsService::generateOfferLetter($payload);
      Artifact::add($taskId, 'offerDoc', $offerDoc);
      RunLog::add($taskId, 'Docs.generateOfferLetter', 'ok', 'Offer document generated', ['doc' => $offerDoc]);

      // Step 2: Notify candidate
      MessagingService::sendOffer($payload['candidate'], $payload['role'], $offerDoc);
      RunLog::add($taskId, 'Messaging.sendOffer', 'ok', 'Offer sent to candidate');

      Task::setStatus($taskId, 'done');
    } catch (Exception $e) {
      RunLog::add($taskId, 'error', 'failed', $e->getMessage());
      Task::setStatus($taskId, 'error');
    }
  }
}
