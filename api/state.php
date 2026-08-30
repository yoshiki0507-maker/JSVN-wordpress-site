<?php
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['authed'])) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthenticated']);
    exit;
}

require __DIR__ . '/../lib/db.php';

$pdo = get_pdo();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query('SELECT data, updated_at FROM app_state WHERE id = 1');
    $row = $stmt->fetch();
    if (!$row) {
        http_response_code(500);
        echo json_encode(['error' => 'not_initialized']);
        exit;
    }
    echo json_encode(['data' => $row['data'], 'updatedAt' => $row['updated_at']]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw = file_get_contents('php://input');
    $body = json_decode($raw, true);
    if (!is_array($body) || !isset($body['data']) || !is_string($body['data'])) {
        http_response_code(400);
        echo json_encode(['error' => 'invalid_body']);
        exit;
    }
    if (strlen($body['data']) > 5_000_000) {
        http_response_code(413);
        echo json_encode(['error' => 'too_large']);
        exit;
    }
    json_decode($body['data']);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'invalid_json']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE app_state SET data = :data, updated_at = NOW() WHERE id = 1');
    $stmt->execute(['data' => $body['data']]);

    $stmt = $pdo->query('SELECT updated_at FROM app_state WHERE id = 1');
    $row = $stmt->fetch();
    echo json_encode(['ok' => true, 'updatedAt' => $row['updated_at']]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'method_not_allowed']);
