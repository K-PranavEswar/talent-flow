<?php
http_response_code(201);
header('Content-Type: application/json');
echo json_encode(['ticketId' => 'INC' . rand(1000, 9999)]);
