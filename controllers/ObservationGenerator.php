<?php
/* ============================================================================
 * OBSERVATION GENERATOR - Generador automático de observaciones para libretas
 * Usa plantillas inteligentes basadas en notas y etiquetas
 * ==========================================================================*/

class ObservationGenerator {
    
    /**
     * Genera una observación automática basada en la nota y etiquetas
     * @param float $score Nota del estudiante (0-100)
     * @param array $tags Etiquetas seleccionadas por el docente
     * @param string $subject Nombre de la materia
     * @return string Observación generada
     */
    public static function generate($score, $tags = [], $subject = 'la materia') {
        $score = (float)$score;
        $tags = is_array($tags) ? $tags : [];
        
        // Determinar nivel de rendimiento
        $level = self::getPerformanceLevel($score);
        
        // Plantillas base según nivel
        $templates = self::getBaseTemplates($level, $subject);
        
        // Agregar modificaciones según etiquetas
        $modifications = self::applyTagModifications($tags, $level);
        
        // Construir observación final
        $observation = $templates['base'] . ' ' . $modifications . ' ' . $templates['recommendation'];
        
        return trim($observation);
    }
    
    /**
     * Determina el nivel de rendimiento según la nota
     */
    private static function getPerformanceLevel($score) {
        if ($score >= 90) return 'excellent';
        if ($score >= 75) return 'good';
        if ($score >= 60) return 'average';
        if ($score >= 51) return 'below_average';
        return 'failing';
    }
    
    /**
     * Plantillas base según nivel de rendimiento
     */
    private static function getBaseTemplates($level, $subject) {
        $templates = [
            'excellent' => [
                'base' => "El estudiante demuestra un dominio excepcional de {$subject}, mostrando comprensión profunda de los conceptos.",
                'recommendation' => "Se recomienda continuar con este excelente ritmo de trabajo y considerar desafíos adicionales para mantener su motivación."
            ],
            'good' => [
                'base' => "El estudiante tiene un buen desempeño en {$subject}, con comprensión sólida de la mayoría de los temas.",
                'recommendation' => "Se sugiere reforzar los temas donde mostró dificultades menores para alcanzar la excelencia."
            ],
            'average' => [
                'base' => "El estudiante muestra un rendimiento aceptable en {$subject}, con comprensión básica de los contenidos.",
                'recommendation' => "Es necesario dedicar más tiempo al estudio y práctica para mejorar la comprensión de los temas."
            ],
            'below_average' => [
                'base' => "El estudiante presenta dificultades en {$subject}, con comprensión limitada de varios conceptos clave.",
                'recommendation' => "Se recomienda trabajo adicional en casa, posible apoyo de tutorías y comunicación constante con el docente."
            ],
            'failing' => [
                'base' => "El estudiante no alcanza los mínimos requeridos en {$subject}, mostrando serias dificultades de aprendizaje.",
                'recommendation' => "Es urgente implementar un plan de recuperación, con apoyo familiar intensivo y seguimiento personalizado del docente."
            ]
        ];
        
        return $templates[$level] ?? $templates['average'];
    }
    
    /**
     * Aplica modificaciones según las etiquetas seleccionadas
     */
    private static function applyTagModifications($tags, $level) {
        $modifications = [];
        
        $tagEffects = [
            'falta_atencion' => 'Se observa falta de atención en clase, lo que afecta su aprendizaje.',
            'buen_comportamiento' => 'Mantiene un comportamiento ejemplar en el aula.',
            'mal_comportamiento' => 'Presenta problemas de disciplina que interfieren con su aprendizaje.',
            'dificultad_especifica' => 'Muestra dificultades específicas en ciertos temas que requieren atención.',
            'participacion_activa' => 'Participa activamente en clase, mostrando interés genuino.',
            'trabajo_en_equipo' => 'Demuestra buenas habilidades de trabajo colaborativo.',
            'tareas_incompletas' => 'Frecuentemente no completa las tareas asignadas.',
            'asistencia_irregular' => 'Su asistencia irregular afecta su continuidad en el aprendizaje.',
            'esfuerzo_notable' => 'Muestra un esfuerzo notable por mejorar, a pesar de las dificultades.',
            'necesita_apoyo_familiar' => 'Requiere mayor involucramiento y apoyo del entorno familiar.'
        ];
        
        foreach ($tags as $tag) {
            if (isset($tagEffects[$tag])) {
                $modifications[] = $tagEffects[$tag];
            }
        }
        
        return implode(' ', $modifications);
    }
    
    /**
     * Obtiene la lista de etiquetas disponibles
     */
    public static function getAvailableTags() {
        return [
            'falta_atencion' => 'Falta de atención',
            'buen_comportamiento' => 'Buen comportamiento',
            'mal_comportamiento' => 'Mal comportamiento',
            'dificultad_especifica' => 'Dificultad específica',
            'participacion_activa' => 'Participación activa',
            'trabajo_en_equipo' => 'Trabajo en equipo',
            'tareas_incompletas' => 'Tareas incompletas',
            'asistencia_irregular' => 'Asistencia irregular',
            'esfuerzo_notable' => 'Esfuerzo notable',
            'necesita_apoyo_familiar' => 'Necesita apoyo familiar'
        ];
    }
}