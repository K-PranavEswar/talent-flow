<?php
http_response_code(201);
header('Content-Type: application/json');
echo json_encode(['link' => 'https://docs.local/welcome/' . rand(1000, 9999)]);
