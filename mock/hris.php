<?php
http_response_code(201);
header('Content-Type: application/json');
echo json_encode(['workerId' => 'w_' . rand(1000, 9999)]);
