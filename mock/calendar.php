<?php
http_response_code(201);
header('Content-Type: application/json');
echo json_encode(['eventId' => 'evt_' . rand(1000, 9999)]);
