<?php
require_once __DIR__ . '/../core/bootstrap.php';

function chatbot_query() {
    require_login();
    $input = json_decode(file_get_contents('php://input'), true);
    $question = trim($input['question'] ?? '');
    $u = current_user();
    
    if (empty($question)) {
        return ['error' => 'Por favor escribe tu pregunta'];
    }
    
    $chatbot = new ChatbotModel();
    $response = $chatbot->getResponse($question, $u['id'], $u['role']);
    
    // Registrar la consulta (opcional, para mejorar la IA en el futuro)
    try {
        db()->prepare("INSERT INTO chatbot_logs (user_id, question, created_at) VALUES (?, ?, NOW())")
            ->execute([$u['id'], $question]);
    } catch (Throwable $e) {
        // Ignorar si la tabla no existe aún
    }
    
    return [
        'question' => $question,
        'response' => nl2br(e($response)), // nl2br para que los saltos de línea se vean en HTML
        'timestamp' => date('H:i')
    ];
}