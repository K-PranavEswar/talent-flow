<?php
require_once __DIR__ . '/../Models/Leave.php';
require_once __DIR__ . '/../Models/Run.php';
require_once __DIR__ . '/../Models/Artifact.php';
require_once __DIR__ . '/../Services/MessagingService.php';

class LeaveService {
  public static function processLeaveRequest(int $taskId, array $p): void {
    Task::setStatus($taskId, 'running');
    try {
      // Step 1: Save in Leave table
      $leaveId = Leave::create($p);
      RunLog::add($taskId, 'Leave.create', 'ok', 'Leave request created');
      Artifact::add($taskId, 'leaveId', $leaveId);

      // Step 2: Notify Manager
      MessagingService::notifyManager(
        $p['manager_email'],
        $p['employee_name'],
        [
          'Leave Type' => $p['leave_type'],
          'Duration' => "{$p['start_date']} to {$p['end_date']}"
        ]
      );
      RunLog::add($taskId, 'Messaging.notify', 'ok', 'Manager notified');

      // Step 3: Complete Task
      Task::setStatus($taskId, 'done');
    } catch (Exception $e) {
      RunLog::add($taskId, 'error', 'failed', $e->getMessage());
      Task::setStatus($taskId, 'error');
    }
  }
}
