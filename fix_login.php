<?php require_once __DIR__ . '/core/bootstrap.php'; echo "<h2>Verificación de Login</h2>"; $test = authenticate_user('admin','Admin123!'); echo $test ? "✅ Login admin: OK" : "❌ Login admin: FALLA";
