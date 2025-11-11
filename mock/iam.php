<?php
http_response_code(201);
header('Content-Type: application/json');
echo json_encode(['userId' => 'u_' . rand(1000, 9999)]);
