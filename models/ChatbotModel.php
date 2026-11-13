<?php
require_once __DIR__ . '/Model.php';

class ChatbotModel extends Model {
    protected $table = 'chatbot_logs'; // Opcional, para registrar consultas

    /**
     * Procesa la pregunta del usuario y devuelve una respuesta inteligente
     */
    public function getResponse($question, $userId, $userRole) {
        $question = mb_strtolower(trim($question ?? ''));

        // 1. INTENCIÓN: CONSULTAS PERSONALIZADAS (Requieren Base de Datos)
        if ($this->containsAny($question, ['mi horario', 'mis clases', 'hora'])) {
            return $this->getPersonalSchedule($userId, $userRole);
        }

        if ($this->containsAny($question, ['falta', 'asistencia', 'retraso', 'licencia'])) {
            return $this->getPersonalAttendance($userId, $userRole);
        }

        if ($this->containsAny($question, ['aviso', 'comunicado', 'reunion', 'evento'])) {
            return $this->getLatestAnnouncements();
        }

        // 2. INTENCIÓN: CONOCIMIENTO GENERAL DEL COLEGIO
        if ($this->containsAny($question, ['direccion', 'telefono', 'contacto', 'ubicacion'])) {
            return "📍 La U.E. Paulo Freire está ubicada en el Distrito 2 de Sacaba. Teléfono: (591-4) 4XXXXXX. Horario de atención de secretaría: 08:00 a 12:30 y 14:30 a 18:00.";
        }

        if ($this->containsAny($question, ['contrasena', 'clave', 'no puedo entrar'])) {
            return "🔒 Para restablecer tu contraseña, dirígete a 'Mi Perfil' en el menú lateral. Si el problema persiste, acércate a la secretaría con tu cédula de identidad o RUDE.";
        }

        if ($this->containsAny($question, ['trimestre', 'cuando', 'fecha'])) {
            return "📅 El año escolar se divide en 3 trimestres. Puedes ver las fechas exactas de exámenes y vacaciones en la sección 'Calendario' del menú.";
        }

        // 3. RESPUESTA POR DEFECTO
        return "🤖 No estoy seguro de entender tu pregunta. Puedo ayudarte con: \n- '¿Cuál es mi horario?'\n- '¿Tengo faltas?'\n- '¿Hay algún comunicado nuevo?'\n- '¿Cuál es el horario de secretaría?'";
    }

    private function containsAny($text, $keywords) {
        foreach ($keywords as $keyword) {
            if (mb_strpos($text, $keyword) !== false) return true;
        }
        return false;
    }

    private function getPersonalSchedule($userId, $role) {
        if ($role === 'estudiante') {
            $st = db()->prepare("SELECT s.first_name, s.last_name, c.name AS course FROM students s JOIN courses c ON c.id = s.course_id WHERE s.user_id = ?");
            $st->execute([$userId]);
            $student = $st->fetch();
            if ($student) {
                return "📚 Hola {$student['first_name']}. Tu horario de clases para {$student['course']} está disponible en la pestaña 'Horarios' del menú lateral. Allí verás día, hora, materia y aula.";
            }
        } elseif ($role === 'docente') {
            return "👨‍🏫 Puedes consultar tu horario completo de clases asignadas en la sección 'Horarios' del menú lateral.";
        }
        return "⚠️ No pude encontrar tu horario. Asegúrate de estar logueado con tu cuenta de estudiante o docente.";
    }

    private function getPersonalAttendance($userId, $role) {
        if ($role === 'estudiante' || $role === 'padre') {
            if ($role === 'estudiante') {
                $st = db()->prepare("SELECT id FROM students WHERE user_id = ? LIMIT 1");
                $st->execute([$userId]);
                $result = $st->fetch();
                $studentId = $result ? (int)$result['id'] : 0;
            } else {
                $st = db()->prepare("SELECT student_id FROM user_students WHERE user_id = ? LIMIT 1");
                $st->execute([$userId]);
                $result = $st->fetch();
                $studentId = $result ? (int)$result['student_id'] : 0;
            }

            if ($studentId) {
                $st = db()->prepare("SELECT COUNT(*) as f FROM attendance WHERE student_id = ? AND status = 'F'");
                $st->execute([$studentId]);
                $faltas = $st->fetch()['f'];
                return "📋 Hasta la fecha, el registro muestra **{$faltas} falta(s)**. Te recomiendo revisar la pestaña 'Asistencia' para ver el detalle de fechas y justificativos.";
            }
        }
        return "⚠️ No pude acceder a tu registro de asistencia. Consulta con tu asesor o en la pestaña 'Asistencia'.";
    }

    private function getLatestAnnouncements() {
        $st = db()->query("SELECT title, created_at FROM announcements WHERE target_type = 'global' ORDER BY created_at DESC LIMIT 2");
        $avisos = $st->fetchAll();
        if (!empty($avisos)) {
            $msg = "📢 Últimos comunicados globales:\n";
            foreach ($avisos as $a) {
                $msg .= "- **{$a['title']}** ({$a['created_at']})\n";
            }
            $msg .= "Puedes leer el contenido completo en la sección 'Comunicados'.";
            return $msg;
        }
        return "📢 No hay comunicados globales nuevos en este momento. Revisa la sección 'Comunicados' por si hay avisos específicos para tu curso.";
    }
}
