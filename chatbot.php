<?php require_once __DIR__ . '/controllers/ChatbotController.php'; header('Content-Type: application/json'); echo json_encode(chatbot_response($_POST['question'] ?? ''));
