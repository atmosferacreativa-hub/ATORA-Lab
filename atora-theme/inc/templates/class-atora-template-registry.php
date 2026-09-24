<?php
/**
 * Registro de templates ATORA Theme.
 *
 * @package Atora_Learning
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Atora_Template_Registry {

    /** @var array<string,array>|null */
    private static $templates = null;

    /**
     * @return array<string,array>
     */
    public static function get_templates($type = null): array {
        if (null === self::$templates) {
            self::$templates = apply_filters('atora_theme_registered_templates', self::defaults());
        }

        $templates = self::$templates;

        // If the plugin UI is present, mark theme templates as delegated/compat
        // so admin UIs can surface that the plugin is the source of truth.
        if (class_exists('CLMS_UI_Template_Resolver')) {
            foreach ($templates as $k => $t) {
                if (is_array($t)) {
                    $t['theme_delegated'] = true;
                    $templates[$k] = $t;
                }
            }
        }

        if ($type) {
            $type = sanitize_key((string) $type);
            $templates = array_filter($templates, static function ($template) use ($type) {
                return isset($template['type']) && $type === sanitize_key((string) $template['type']);
            });
            $templates = apply_filters('atora_theme_registered_templates_' . $type, $templates, $type);
        }

        return $templates;
    }

    public static function get_template(string $template_id, $type = null): ?array {
        $template_id = sanitize_key($template_id);
        if (!$template_id) {
            return null;
        }

        $templates = self::get_templates($type);

        if (!isset($templates[$template_id])) {
            return null;
        }

        $template = $templates[$template_id];

        // If the plugin UI exists, prefer a thin plugin-shell for the
        // primary template types so the plugin becomes the single source
        // of truth for schema/section rendering. Keep the theme file path
        // pointing to the plugin-shell to avoid duplicating composition.
        $plugin_types = array('course', 'lesson', 'program', 'home', 'landing', 'page', 'post');
        if (class_exists('CLMS_UI_Template_Resolver') && in_array($template['type'] ?? '', $plugin_types, true)) {
            $template['file'] = 'templates/atora/shared/plugin-shell.php';
            $template['admin_hint'] = trim((string) ($template['admin_hint'] ?? '') . ' ' . __('(Delegado al plugin ATORA)', 'atora-learning'));
            $template['theme_delegated'] = true;
        }

        return $template;
    }

    private static function defaults(): array {
        $templates = [
            'course-classic' => self::build_template([
                'type' => 'course',
                'label' => __('Curso clásico', 'atora-learning'),
                'description' => __('Vista académica equilibrada para presentar objetivos, currículo y profesor.', 'atora-learning'),
                'file' => 'templates/atora/course/course-classic.php',
                'preview' => self::preview('course-classic.svg'),
                'sections' => ['course-hero', 'course-curriculum', 'course-teacher-card', 'course-faq'],
                'supports' => ['teachers'],
                'use_case' => __('Ideal para cursos informativos con foco académico.', 'atora-learning'),
                'audience' => __('Visitantes y estudiantes que buscan contexto antes de inscribirse.', 'atora-learning'),
                'complexity' => __('Académico', 'atora-learning'),
                'recommended_for' => [__('Presentación del curso', 'atora-learning'), __('Currículo', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando quieras una página clara, completa y sin exceso comercial.', 'atora-learning'),
            ]),
            'course-commercial' => self::build_template([
                'type' => 'course',
                'label' => __('Curso comercial', 'atora-learning'),
                'description' => __('Landing de venta para visitantes o usuarios no inscritos.', 'atora-learning'),
                'file' => 'templates/atora/course/course-commercial.php',
                'preview' => self::preview('course-commercial.svg'),
                'sections' => ['course-hero', 'course-benefits', 'course-teacher-card', 'course-curriculum', 'course-price-card', 'course-woocommerce-cta', 'course-crm-lead-box', 'course-faq', 'course-related-courses'],
                'supports' => ['woocommerce', 'crm', 'teachers', 'related'],
                'use_case' => __('Ideal para vender cursos, captar leads y activar CTA con WooCommerce.', 'atora-learning'),
                'audience' => __('Visitantes, leads y compradores potenciales.', 'atora-learning'),
                'complexity' => __('Comercial', 'atora-learning'),
                'recommended_for' => [__('Ventas', 'atora-learning'), __('CRM', 'atora-learning'), __('WooCommerce', 'atora-learning')],
                'admin_hint' => __('Recomendado cuando la prioridad es conversión y captación.', 'atora-learning'),
            ]),
            'course-premium' => self::build_template([
                'type' => 'course',
                'label' => __('Curso premium', 'atora-learning'),
                'description' => __('Presentación de alto valor con narrativa visual y prueba social.', 'atora-learning'),
                'file' => 'templates/atora/course/course-premium.php',
                'preview' => self::preview('course-premium.svg'),
                'sections' => ['course-hero', 'course-benefits', 'course-teacher-card', 'course-curriculum', 'course-testimonials', 'course-price-card', 'course-crm-lead-box'],
                'supports' => ['woocommerce', 'crm', 'teachers'],
                'use_case' => __('Perfecto para programas premium con precio alto y testimonios.', 'atora-learning'),
                'audience' => __('Usuarios listos para comparar valor y credenciales.', 'atora-learning'),
                'complexity' => __('Premium', 'atora-learning'),
                'recommended_for' => [__('Marca premium', 'atora-learning'), __('Testimonios', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando necesites posicionar el curso con percepción de alto valor.', 'atora-learning'),
            ]),
            'course-minimal' => self::build_template([
                'type' => 'course',
                'label' => __('Curso minimalista', 'atora-learning'),
                'description' => __('Versión compacta con estructura ligera y navegación simple.', 'atora-learning'),
                'file' => 'templates/atora/course/course-minimal.php',
                'preview' => self::preview('course-minimal.svg'),
                'sections' => ['course-hero', 'course-curriculum', 'course-price-card'],
                'supports' => ['woocommerce'],
                'use_case' => __('Útil para lanzamientos rápidos con contenido directo.', 'atora-learning'),
                'audience' => __('Usuarios que prefieren información breve y clara.', 'atora-learning'),
                'complexity' => __('Minimalista', 'atora-learning'),
                'recommended_for' => [__('Lanzamiento rápido', 'atora-learning'), __('Catálogo corto', 'atora-learning')],
                'admin_hint' => __('Recomendado para cursos con estructura corta y CTA puntual.', 'atora-learning'),
            ]),
            'course-cohort' => self::build_template([
                'type' => 'course',
                'label' => __('Curso de cohorte', 'atora-learning'),
                'description' => __('Diseño para cohortes con narrativa de comunidad y acompañamiento.', 'atora-learning'),
                'file' => 'templates/atora/course/course-cohort.php',
                'preview' => self::preview('course-cohort.svg'),
                'sections' => ['course-hero', 'course-benefits', 'course-teacher-card', 'course-curriculum', 'course-crm-lead-box'],
                'supports' => ['crm', 'teachers'],
                'use_case' => __('Ideal para convocatorias por fechas, cupos o grupos.', 'atora-learning'),
                'audience' => __('Estudiantes que buscan acompañamiento activo.', 'atora-learning'),
                'complexity' => __('Cohorte', 'atora-learning'),
                'recommended_for' => [__('Cohortes', 'atora-learning'), __('Convocatorias', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando el curso tenga inicio por fechas y dinámica grupal.', 'atora-learning'),
            ]),
            'course-student' => self::build_template([
                'type' => 'course',
                'label' => __('Curso para estudiante', 'atora-learning'),
                'description' => __('Vista interna para alumnos inscritos con foco en avance y continuidad.', 'atora-learning'),
                'file' => 'templates/atora/course/course-student.php',
                'preview' => self::preview('course-student.svg'),
                'sections' => ['course-hero', 'course-curriculum', 'course-teacher-card', 'course-related-courses'],
                'supports' => ['teachers', 'related'],
                'use_case' => __('Diseñado para aprendizaje activo después de la compra.', 'atora-learning'),
                'audience' => __('Estudiantes inscritos.', 'atora-learning'),
                'complexity' => __('Aprendizaje', 'atora-learning'),
                'recommended_for' => [__('Progreso', 'atora-learning'), __('Continuidad académica', 'atora-learning')],
                'admin_hint' => __('Configúralo como plantilla interna para usuarios con acceso.', 'atora-learning'),
            ]),

            'lesson-focus' => self::build_template([
                'type' => 'lesson',
                'label' => __('Lección enfoque', 'atora-learning'),
                'description' => __('Modo concentración sin distracciones para estudiar contenido extenso.', 'atora-learning'),
                'file' => 'templates/atora/lesson/lesson-focus.php',
                'preview' => self::preview('lesson-focus.svg'),
                'sections' => ['lesson-header', 'lesson-progress', 'lesson-content', 'lesson-navigation'],
                'supports' => ['progress'],
                'use_case' => __('Lecciones teóricas o clases de lectura profunda.', 'atora-learning'),
                'audience' => __('Estudiantes en sesiones de estudio concentrado.', 'atora-learning'),
                'complexity' => __('Enfoque', 'atora-learning'),
                'recommended_for' => [__('Lectura profunda', 'atora-learning'), __('Sin distracciones', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando quieras priorizar comprensión del contenido.', 'atora-learning'),
            ]),
            'lesson-video' => self::build_template([
                'type' => 'lesson',
                'label' => __('Lección video', 'atora-learning'),
                'description' => __('Lección centrada en reproductor con apoyo de recursos descargables.', 'atora-learning'),
                'file' => 'templates/atora/lesson/lesson-video.php',
                'preview' => self::preview('lesson-video.svg'),
                'sections' => ['lesson-header', 'lesson-video', 'lesson-content', 'lesson-resources', 'lesson-navigation'],
                'supports' => ['progress'],
                'use_case' => __('Clases prácticas donde el video es el eje principal.', 'atora-learning'),
                'audience' => __('Estudiantes que aprenden por demostración visual.', 'atora-learning'),
                'complexity' => __('Video', 'atora-learning'),
                'recommended_for' => [__('Clases grabadas', 'atora-learning'), __('Tutoriales', 'atora-learning')],
                'admin_hint' => __('Recomendado para lecciones con material audiovisual principal.', 'atora-learning'),
            ]),
            'lesson-reading' => self::build_template([
                'type' => 'lesson',
                'label' => __('Lección lectura', 'atora-learning'),
                'description' => __('Plantilla editorial para lecciones con texto y recursos de apoyo.', 'atora-learning'),
                'file' => 'templates/atora/lesson/lesson-reading.php',
                'preview' => self::preview('lesson-reading.svg'),
                'sections' => ['lesson-header', 'lesson-content', 'lesson-resources', 'lesson-progress', 'lesson-navigation'],
                'supports' => ['progress'],
                'use_case' => __('Materiales de estudio, guías y documentación técnica.', 'atora-learning'),
                'audience' => __('Estudiantes que necesitan lectura estructurada.', 'atora-learning'),
                'complexity' => __('Académico', 'atora-learning'),
                'recommended_for' => [__('Lectura', 'atora-learning'), __('Guías descargables', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando la lección sea principalmente textual.', 'atora-learning'),
            ]),
            'lesson-assignment' => self::build_template([
                'type' => 'lesson',
                'label' => __('Lección tarea', 'atora-learning'),
                'description' => __('Diseño orientado a actividades, entregables y criterios de revisión.', 'atora-learning'),
                'file' => 'templates/atora/lesson/lesson-assignment.php',
                'preview' => self::preview('lesson-assignment.svg'),
                'sections' => ['lesson-header', 'lesson-content', 'lesson-assignment', 'lesson-resources', 'lesson-navigation'],
                'supports' => ['progress'],
                'use_case' => __('Perfecta para prácticas y actividades evaluables.', 'atora-learning'),
                'audience' => __('Estudiantes en fase de aplicación práctica.', 'atora-learning'),
                'complexity' => __('Tarea', 'atora-learning'),
                'recommended_for' => [__('Entregables', 'atora-learning'), __('Prácticas', 'atora-learning')],
                'admin_hint' => __('Recomendado cuando la acción principal es enviar una tarea.', 'atora-learning'),
            ]),
            'lesson-live' => self::build_template([
                'type' => 'lesson',
                'label' => __('Lección en vivo', 'atora-learning'),
                'description' => __('Estructura para sesiones sincrónicas con enlace de transmisión.', 'atora-learning'),
                'file' => 'templates/atora/lesson/lesson-live.php',
                'preview' => self::preview('lesson-live.svg'),
                'sections' => ['lesson-header', 'lesson-video', 'lesson-content', 'lesson-resources', 'lesson-navigation'],
                'supports' => ['progress'],
                'use_case' => __('Clases en directo, mentorías o webinars.', 'atora-learning'),
                'audience' => __('Cohortes activas y sesiones programadas.', 'atora-learning'),
                'complexity' => __('En vivo', 'atora-learning'),
                'recommended_for' => [__('Sesiones sincrónicas', 'atora-learning'), __('Webinars', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando la lección dependa de una sesión programada.', 'atora-learning'),
            ]),
            'lesson-evaluation' => self::build_template([
                'type' => 'lesson',
                'label' => __('Lección evaluación', 'atora-learning'),
                'description' => __('Plantilla para pruebas, rúbricas y revisión de desempeño.', 'atora-learning'),
                'file' => 'templates/atora/lesson/lesson-evaluation.php',
                'preview' => self::preview('lesson-evaluation.svg'),
                'sections' => ['lesson-header', 'lesson-content', 'lesson-evaluation', 'lesson-navigation'],
                'supports' => ['progress'],
                'use_case' => __('Exámenes, cuestionarios o entregas calificadas.', 'atora-learning'),
                'audience' => __('Estudiantes en validación de competencias.', 'atora-learning'),
                'complexity' => __('Evaluación', 'atora-learning'),
                'recommended_for' => [__('Rúbricas', 'atora-learning'), __('Evaluaciones', 'atora-learning')],
                'admin_hint' => __('Recomendado cuando la lección mida logro académico.', 'atora-learning'),
            ]),

            'program-diploma' => self::build_template([
                'type' => 'program',
                'label' => __('Programa diplomado', 'atora-learning'),
                'description' => __('Ruta completa de formación con certificación y estructura modular.', 'atora-learning'),
                'file' => 'templates/atora/program/program-diploma.php',
                'preview' => self::preview('program-diploma.svg'),
                'sections' => ['program-hero', 'program-diploma-path', 'program-courses', 'program-teachers', 'program-certification', 'program-faq', 'program-crm-lead-box'],
                'supports' => ['crm', 'teachers'],
                'use_case' => __('Ideal para mostrar un plan académico de largo recorrido.', 'atora-learning'),
                'audience' => __('Aspirantes a diplomados y rutas de especialización.', 'atora-learning'),
                'complexity' => __('Diplomado', 'atora-learning'),
                'recommended_for' => [__('Ruta académica', 'atora-learning'), __('Certificación', 'atora-learning')],
                'admin_hint' => __('Úsalo para programas con módulos, docentes y certificación final.', 'atora-learning'),
            ]),
            'program-academy' => self::build_template([
                'type' => 'program',
                'label' => __('Programa academia', 'atora-learning'),
                'description' => __('Vista para estudiantes inscritos en programas internos.', 'atora-learning'),
                'file' => 'templates/atora/program/program-academy.php',
                'preview' => self::preview('program-academy.svg'),
                'sections' => ['program-hero', 'program-courses', 'program-teachers', 'program-certification'],
                'supports' => ['teachers'],
                'use_case' => __('Gestión académica de programas ya contratados.', 'atora-learning'),
                'audience' => __('Estudiantes activos del programa.', 'atora-learning'),
                'complexity' => __('Académico', 'atora-learning'),
                'recommended_for' => [__('Seguimiento interno', 'atora-learning'), __('Plan de estudio', 'atora-learning')],
                'admin_hint' => __('Recomendado como vista interna del programa para inscritos.', 'atora-learning'),
            ]),
            'program-commercial' => self::build_template([
                'type' => 'program',
                'label' => __('Programa comercial', 'atora-learning'),
                'description' => __('Landing de venta para programas con foco en conversión.', 'atora-learning'),
                'file' => 'templates/atora/program/program-commercial.php',
                'preview' => self::preview('program-commercial.svg'),
                'sections' => ['program-hero', 'program-diploma-path', 'program-courses', 'program-teachers', 'program-faq', 'program-crm-lead-box'],
                'supports' => ['crm', 'teachers'],
                'use_case' => __('Captación de leads y venta de programas formativos.', 'atora-learning'),
                'audience' => __('Visitantes y prospectos corporativos o individuales.', 'atora-learning'),
                'complexity' => __('Comercial', 'atora-learning'),
                'recommended_for' => [__('Conversión', 'atora-learning'), __('CRM', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando el objetivo principal sea vender el programa.', 'atora-learning'),
            ]),
            'program-institutional' => self::build_template([
                'type' => 'program',
                'label' => __('Programa institucional', 'atora-learning'),
                'description' => __('Presentación formal para alianzas y programas institucionales.', 'atora-learning'),
                'file' => 'templates/atora/program/program-institutional.php',
                'preview' => self::preview('program-institutional.svg'),
                'sections' => ['program-hero', 'program-courses', 'program-teachers', 'program-certification', 'program-faq'],
                'supports' => ['teachers'],
                'use_case' => __('Mostrar credenciales, estructura y propuesta institucional.', 'atora-learning'),
                'audience' => __('Empresas, aliados y organizaciones educativas.', 'atora-learning'),
                'complexity' => __('Institucional', 'atora-learning'),
                'recommended_for' => [__('Convenios', 'atora-learning'), __('Alianzas', 'atora-learning')],
                'admin_hint' => __('Recomendado para comunicación institucional y convenios.', 'atora-learning'),
            ]),

            'home-academy' => self::build_template([
                'type' => 'home',
                'label' => __('Home academia', 'atora-learning'),
                'description' => __('Inicio equilibrado para mostrar cursos, programas y docentes.', 'atora-learning'),
                'file' => 'templates/atora/site/home-academy.php',
                'preview' => self::preview('home-academy.svg'),
                'sections' => ['site-hero-academy', 'site-featured-courses', 'site-featured-programs', 'site-teacher-grid', 'site-stats', 'site-testimonials', 'site-cta'],
                'supports' => ['teachers'],
                'use_case' => __('Portada principal para academias con catálogo mixto.', 'atora-learning'),
                'audience' => __('Visitantes nuevos y comunidad académica.', 'atora-learning'),
                'complexity' => __('Académico', 'atora-learning'),
                'recommended_for' => [__('Portada institucional', 'atora-learning'), __('Catálogo', 'atora-learning')],
                'admin_hint' => __('Úsalo como inicio principal cuando conviven cursos y programas.', 'atora-learning'),
            ]),
            'home-commercial' => self::build_template([
                'type' => 'home',
                'label' => __('Home comercial', 'atora-learning'),
                'description' => __('Portada enfocada en embudo, prueba social y llamados a la acción.', 'atora-learning'),
                'file' => 'templates/atora/site/home-commercial.php',
                'preview' => self::preview('home-commercial.svg'),
                'sections' => ['site-hero-academy', 'site-featured-courses', 'site-stats', 'site-testimonials', 'site-cta'],
                'supports' => ['crm'],
                'use_case' => __('Ideal para campañas de captación y crecimiento comercial.', 'atora-learning'),
                'audience' => __('Visitantes fríos y leads de campañas.', 'atora-learning'),
                'complexity' => __('Comercial', 'atora-learning'),
                'recommended_for' => [__('Embudo', 'atora-learning'), __('Campañas', 'atora-learning')],
                'admin_hint' => __('Recomendado cuando necesitas priorizar conversión en home.', 'atora-learning'),
            ]),
            'home-institutional' => self::build_template([
                'type' => 'home',
                'label' => __('Home institucional', 'atora-learning'),
                'description' => __('Portada corporativa con enfoque en reputación y respaldo.', 'atora-learning'),
                'file' => 'templates/atora/site/home-institutional.php',
                'preview' => self::preview('home-institutional.svg'),
                'sections' => ['site-hero-academy', 'site-stats', 'site-teacher-grid', 'site-blog-latest', 'site-cta'],
                'supports' => ['teachers'],
                'use_case' => __('Mostrar marca, docentes y producción editorial.', 'atora-learning'),
                'audience' => __('Instituciones, aliados y público general.', 'atora-learning'),
                'complexity' => __('Institucional', 'atora-learning'),
                'recommended_for' => [__('Reputación', 'atora-learning'), __('Docentes', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando la marca institucional sea la prioridad.', 'atora-learning'),
            ]),

            'landing-course' => self::build_template([
                'type' => 'landing',
                'label' => __('Landing de curso', 'atora-learning'),
                'description' => __('Página de conversión dedicada para vender un curso específico.', 'atora-learning'),
                'file' => 'templates/atora/site/landing-course.php',
                'preview' => self::preview('landing-course.svg'),
                'sections' => ['course-hero', 'course-benefits', 'course-teacher-card', 'course-curriculum', 'course-price-card', 'site-crm-lead-box', 'course-faq'],
                'supports' => ['crm', 'woocommerce'],
                'use_case' => __('Campañas de pago, email marketing y afiliados.', 'atora-learning'),
                'audience' => __('Leads de campañas con intención de compra.', 'atora-learning'),
                'complexity' => __('Comercial', 'atora-learning'),
                'recommended_for' => [__('Anuncios', 'atora-learning'), __('Conversión', 'atora-learning')],
                'admin_hint' => __('Recomendado para una oferta puntual de un curso.', 'atora-learning'),
            ]),
            'landing-program' => self::build_template([
                'type' => 'landing',
                'label' => __('Landing de programa', 'atora-learning'),
                'description' => __('Página de conversión enfocada en programas o diplomados.', 'atora-learning'),
                'file' => 'templates/atora/site/landing-program.php',
                'preview' => self::preview('landing-program.svg'),
                'sections' => ['program-hero', 'program-diploma-path', 'program-courses', 'program-teachers', 'site-crm-lead-box', 'program-faq'],
                'supports' => ['crm'],
                'use_case' => __('Campañas de alto ticket para programas completos.', 'atora-learning'),
                'audience' => __('Prospectos que evalúan rutas de formación amplias.', 'atora-learning'),
                'complexity' => __('Comercial', 'atora-learning'),
                'recommended_for' => [__('Diplomados', 'atora-learning'), __('Lead nurturing', 'atora-learning')],
                'admin_hint' => __('Úsalo para campañas específicas de programas.', 'atora-learning'),
            ]),

            'page-fullwidth' => self::build_template([
                'type' => 'page',
                'label' => __('Página ancho completo', 'atora-learning'),
                'description' => __('Plantilla limpia para contenido editorial sin barras laterales.', 'atora-learning'),
                'file' => 'templates/atora/site/page-fullwidth.php',
                'preview' => self::preview('page-fullwidth.svg'),
                'sections' => ['shared-notice'],
                'supports' => [],
                'use_case' => __('Contenido institucional o informativo general.', 'atora-learning'),
                'audience' => __('Lectores y visitantes de contenido de marca.', 'atora-learning'),
                'complexity' => __('Base', 'atora-learning'),
                'recommended_for' => [__('Contenido largo', 'atora-learning')],
                'admin_hint' => __('Úsalo cuando no necesites bloques comerciales adicionales.', 'atora-learning'),
            ]),
            'page-sales' => self::build_template([
                'type' => 'page',
                'label' => __('Página de ventas', 'atora-learning'),
                'description' => __('Página comercial con narrativa breve y CTA principal.', 'atora-learning'),
                'file' => 'templates/atora/site/page-sales.php',
                'preview' => self::preview('page-sales.svg'),
                'sections' => ['site-hero-academy', 'course-benefits', 'site-cta', 'site-crm-lead-box'],
                'supports' => ['crm'],
                'use_case' => __('Oferta comercial rápida fuera de cursos o programas.', 'atora-learning'),
                'audience' => __('Leads en fase de decisión.', 'atora-learning'),
                'complexity' => __('Comercial', 'atora-learning'),
                'recommended_for' => [__('Campañas', 'atora-learning'), __('Ofertas', 'atora-learning')],
                'admin_hint' => __('Recomendado para páginas de venta con CTA único.', 'atora-learning'),
            ]),
            'page-institutional' => self::build_template([
                'type' => 'page',
                'label' => __('Página institucional', 'atora-learning'),
                'description' => __('Página corporativa para presentar la academia u organización.', 'atora-learning'),
                'file' => 'templates/atora/site/page-institutional.php',
                'preview' => self::preview('page-institutional.svg'),
                'sections' => ['site-hero-academy', 'site-stats', 'site-cta'],
                'supports' => [],
                'use_case' => __('Quiénes somos, misión, aliados o servicios.', 'atora-learning'),
                'audience' => __('Aliados, prensa y público institucional.', 'atora-learning'),
                'complexity' => __('Institucional', 'atora-learning'),
                'recommended_for' => [__('Marca', 'atora-learning'), __('Reputación', 'atora-learning')],
                'admin_hint' => __('Úsalo para reforzar credibilidad institucional.', 'atora-learning'),
            ]),
            'teacher-profile' => self::build_template([
                'type' => 'page',
                'label' => __('Perfil de profesor', 'atora-learning'),
                'description' => __('Ficha visual para docentes con biografía y cursos relacionados.', 'atora-learning'),
                'file' => 'templates/atora/site/teacher-profile.php',
                'preview' => self::preview('teacher-profile.svg'),
                'sections' => [],
                'supports' => ['teachers'],
                'use_case' => __('Presentar credenciales de cada profesor.', 'atora-learning'),
                'audience' => __('Estudiantes que evalúan experiencia docente.', 'atora-learning'),
                'complexity' => __('Perfil', 'atora-learning'),
                'recommended_for' => [__('Docentes', 'atora-learning'), __('Autoridad', 'atora-learning')],
                'admin_hint' => __('Recomendado para fortalecer confianza en el equipo docente.', 'atora-learning'),
            ]),

            'post-editorial' => self::build_template([
                'type' => 'post',
                'label' => __('Entrada editorial', 'atora-learning'),
                'description' => __('Plantilla para artículos con lectura cómoda y estilo de revista.', 'atora-learning'),
                'file' => 'templates/atora/site/post-editorial.php',
                'preview' => self::preview('post-editorial.svg'),
                'sections' => ['shared-breadcrumbs', 'shared-notice'],
                'supports' => [],
                'use_case' => __('Blog académico, noticias o recursos de valor.', 'atora-learning'),
                'audience' => __('Lectores orgánicos y comunidad de contenidos.', 'atora-learning'),
                'complexity' => __('Editorial', 'atora-learning'),
                'recommended_for' => [__('Blog', 'atora-learning'), __('SEO', 'atora-learning')],
                'admin_hint' => __('Úsalo para mantener consistencia visual en el blog.', 'atora-learning'),
            ]),
        ];

        $section_files = [
            'course-hero' => 'course/hero.php',
            'course-benefits' => 'course/benefits.php',
            'course-curriculum' => 'course/curriculum.php',
            'course-teacher-card' => 'course/teacher-card.php',
            'course-price-card' => 'course/price-card.php',
            'course-faq' => 'course/faq.php',
            'course-testimonials' => 'course/testimonials.php',
            'course-related-courses' => 'course/related-courses.php',
            'course-crm-lead-box' => 'course/crm-lead-box.php',
            'course-woocommerce-cta' => 'course/woocommerce-cta.php',
            'lesson-header' => 'lesson/header.php',
            'lesson-sidebar' => 'lesson/sidebar.php',
            'lesson-video' => 'lesson/video.php',
            'lesson-content' => 'lesson/content.php',
            'lesson-resources' => 'lesson/resources.php',
            'lesson-progress' => 'lesson/progress.php',
            'lesson-navigation' => 'lesson/navigation.php',
            'lesson-assignment' => 'lesson/assignment.php',
            'lesson-evaluation' => 'lesson/evaluation.php',
            'program-hero' => 'program/hero.php',
            'program-courses' => 'program/courses.php',
            'program-diploma-path' => 'program/diploma-path.php',
            'program-teachers' => 'program/teachers.php',
            'program-certification' => 'program/certification.php',
            'program-faq' => 'program/faq.php',
            'program-crm-lead-box' => 'program/crm-lead-box.php',
            'site-hero-academy' => 'site/hero-academy.php',
            'site-featured-courses' => 'site/featured-courses.php',
            'site-featured-programs' => 'site/featured-programs.php',
            'site-teacher-grid' => 'site/teacher-grid.php',
            'site-stats' => 'site/stats.php',
            'site-testimonials' => 'site/testimonials.php',
            'site-blog-latest' => 'site/blog-latest.php',
            'site-cta' => 'site/cta.php',
            'site-crm-lead-box' => 'site/crm-lead-box.php',
            'shared-breadcrumbs' => 'shared/breadcrumbs.php',
            'shared-empty-state' => 'shared/empty-state.php',
            'shared-access-denied' => 'shared/access-denied.php',
            'shared-loading-card' => 'shared/loading-card.php',
            'shared-badge' => 'shared/badge.php',
            'shared-notice' => 'shared/notice.php',
        ];

        foreach ($section_files as $section_id => $file) {
            $templates[$section_id] = self::build_template([
                'type' => 'section',
                'label' => ucwords(str_replace('-', ' ', $section_id)),
                'description' => __('Sección reutilizable.', 'atora-learning'),
                'file' => 'templates/atora/sections/' . $file,
                'sections' => [],
                'supports' => [],
                'use_case' => __('Bloque interno reutilizable del sistema de templates.', 'atora-learning'),
                'audience' => __('Uso técnico interno.', 'atora-learning'),
                'complexity' => __('Sistema', 'atora-learning'),
                'recommended_for' => [],
                'admin_hint' => __('No activar como plantilla principal.', 'atora-learning'),
            ]);
        }

        return $templates;
    }

    private static function build_template(array $template): array {
        $template = wp_parse_args($template, [
            'type' => '',
            'label' => '',
            'description' => '',
            'file' => '',
            'preview' => '',
            'use_case' => '',
            'audience' => '',
            'complexity' => '',
            'recommended_for' => [],
            'sections' => [],
            'supports' => [],
            'admin_hint' => '',
        ]);

        $template['recommended_for'] = array_values(array_filter(array_map('sanitize_text_field', (array) $template['recommended_for'])));
        $template['sections'] = array_values(array_filter(array_map('sanitize_key', (array) $template['sections'])));
        $template['supports'] = array_values(array_filter(array_map('sanitize_key', (array) $template['supports'])));
        $template['preview'] = ltrim((string) $template['preview'], '/');

        return $template;
    }

    private static function preview(string $filename): string {
        return 'assets/images/template-previews/' . sanitize_file_name($filename);
    }
}

if (!function_exists('atora_theme_get_registered_templates')) {
    function atora_theme_get_registered_templates($type = null) {
        return Atora_Template_Registry::get_templates($type);
    }
}

if (!function_exists('atora_theme_get_template')) {
    function atora_theme_get_template($template_id, $type = null) {
        return Atora_Template_Registry::get_template((string) $template_id, $type);
    }
}
