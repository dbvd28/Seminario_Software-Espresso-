<?php
/**
 * PHP Version 7.2
 *
 * @category Public
 * @package  Controllers
 * @author   Gemini AI
 * @license  MIT http://
 * @version  CVS:1.0.0
 * @link     http://
 */
namespace Controllers;

use \Utilities\Site; 
use \Views\Renderer;

/**
 * Eventos Controller
 *
 * @category Public
 * @package  Controllers
 * @author   Gemini AI
 * @license  MIT http://
 * @link     http://
 */
class Eventos extends PublicController
{
    /**
     * Eventos run method
     *
     * @return void
     */
    public function run(): void
    {
        // Inicializamos los datos
        $viewData = array();
        $viewData["page_title"] = "Próximos Eventos y Catas";
        
        // 1. Definición de Eventos (4 tarjetas)
        $eventos = [
            [
                'id' => 1,
                'imagen' => 'public/imgs/hero/independencia.jpg',
                'titulo' => 'Noche de Música en Vivo',
                'descripcionCorta' => 'Disfrutá de una noche especial con bandas locales y jazz.',
                'descripcionLarga' => '¡Una velada inolvidable de música en vivo! Contaremos con la participación de talentosos artistas locales que tocarán una variedad de géneros, desde jazz suave hasta blues. Incluye un café de especialidad de cortesía.',
                'fecha' => 'Viernes 15 Nov'
            ],
            [
                'id' => 2,
                'imagen' => 'public/imgs/hero/nino.jpg',
                'titulo' => 'Taller de Barismo Avanzado',
                'descripcionCorta' => 'Aprendé los secretos del café, técnicas de Latte Art y brew.',
                'descripcionLarga' => 'Sumérgete en el mundo del café con nuestro taller avanzado. Cubriremos la selección del grano, tueste, técnicas de preparación (V60, Chemex) y una introducción al Latte Art. Cupos limitados.',
                'fecha' => 'Sábado 22 Nov'
            ],
            [
                'id' => 3,
                'imagen' => 'public/imgs/hero/madre.jpg',
                'titulo' => 'Degustación de Postres',
                'descripcionCorta' => 'Probá nuestras nuevas creaciones de repostería maridadas con café.',
                'descripcionLarga' => 'Un evento para deleitar el paladar. Presentaremos 5 postres nuevos creados por nuestro chef, maridados cuidadosamente con 5 variedades de café de origen hondureño. ¡Una experiencia gourmet!',
                'fecha' => 'Viernes 29 Nov'
            ],
            [
                'id' => 4,
                'imagen' => 'public/imgs/hero/navidad.jpg',
                'titulo' => 'Club de Lectura "Café y Letras"',
                'descripcionCorta' => 'Compartí tus libros favoritos con otros amantes de la lectura.',
                'descripcionLarga' => 'Nuestro club de lectura mensual se reúne para discutir la obra "Cien Años de Soledad". Ambiente relajado, buena conversación y un refill ilimitado de nuestro café de la casa. ¡Trae tu libro!',
                'fecha' => 'Jueves 05 Dic'
            ]
        ];

        $viewData["eventos"] = $eventos;
        
        // VERIFICA QUE ESTA LÍNEA ESTÉ PRESENTE:
        Site::addLink("public/css/eventos.css"); 
        
        // Renderizamos la plantilla
        Renderer::render('eventos', $viewData);
    }
}