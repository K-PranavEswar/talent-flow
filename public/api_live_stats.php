<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../app/Models/Task.php';
require_once __DIR__ . '/../app/Models/Leave.php';

$completed = count(array_filter(Task::all(), fn($t) => $t['status'] === 'done'));
$leaveRequests = count(Leave::all());

echo json_encode([
  'timestamp' => date('H:i:s'),
  'completedTasks' => rand(0, $completed), // or real-time diff
  'leaveRequests' => rand(0, $leaveRequests)
]);
