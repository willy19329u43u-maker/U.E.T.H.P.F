<?php
require_once __DIR__ . '/../core/bootstrap.php';

function alerts_page() {
    require_perm('reports');
    $alertModel = new AlertModel();
    
    // Ejecutar análisis
    $alerts = $alertModel->analyzeAtRiskStudents();
    $alertModel->saveAlerts($alerts);
    
    // Obtener alertas agrupadas por severidad
    $highAlerts = array_filter($alerts, fn($a) => $a['severity'] === 'high');
    $mediumAlerts = array_filter($alerts, fn($a) => $a['severity'] === 'medium');
    
    return [
        'all' => $alerts,
        'high' => $highAlerts,
        'medium' => $mediumAlerts,
        'total' => count($alerts)
    ];
}