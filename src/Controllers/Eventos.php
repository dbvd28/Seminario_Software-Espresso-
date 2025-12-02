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
        'imagen' => 'public/imgs/hero/navidad.jpg',
        'titulo' => 'Fiesta Navideña',
        'descripcionCorta' => 'Celebra la magia de la Navidad con una velada especial llena de alegría y sorpresas.',
        'descripcionLarga' => 'Únete a nuestra encantadora Fiesta Navideña donde el espíritu festivo cobra vida. Disfruta de deliciosos postres navideños, bebidas calientes especiales, música festiva y un ambiente acogedor perfecto para compartir con amigos y familia. ¡Santa Claus nos visitará con regalos sorpresa!',
        'fecha' => 'Miércoles 24 Diciembre'
        ],
        [
        'id' => 2,
        'imagen' => 'public/imgs/hero/madre.jpg',
        'titulo' => 'Día de las Madres',
        'descripcionCorta' => 'Homenajea a la reina de casa con una experiencia única creada para ella.',
        'descripcionLarga' => 'Honra a mamá en su día con nuestro evento especial. Hemos preparado un brunch exquisito, regalos sorpresa, música relajante y un espacio dedicado para que las madres se sientan verdaderamente especiales. ¡Incluye un retrato profesional gratuito para mamá!',
        'fecha' => 'Domingo 10 Mayo'
        ],
        [
        'id' => 3,
        'imagen' => 'public/imgs/hero/nino.jpg',
        'titulo' => 'Día del Niño',
        'descripcionCorta' => 'Una jornada mágica llena de diversión, juegos y sorpresas para los más pequeños.',
        'descripcionLarga' => 'Los niños son los protagonistas en esta fiesta especial. Tendremos juegos interactivos, show de magia, taller de manualidades, pintacaritas, algodón de azúcar y muchas sorpresas más. Un espacio seguro y divertido donde crearán recuerdos inolvidables.',
        'fecha' => 'Jueves 10 Septiembre'
        ],
        [
        'id' => 4,
        'imagen' => 'public/imgs/hero/independencia.jpg',
        'titulo' => 'Día de la Independencia de Honduras',
        'descripcionCorta' => 'Vive el orgullo catracho con una auténtica celebración patriótica.',
        'descripcionLarga' => 'Celebremos juntos el patriotismo hondureño con música folclórica en vivo, platillos típicos tradicionales, decoración patriótica y muestras culturales. Una noche para honrar nuestras raíces y disfrutar de la rica cultura catracha en un ambiente festivo y familiar.',
        'fecha' => 'Martes 15 Septiembre'
        ]
        ];

        $viewData["eventos"] = $eventos;
        
        // VERIFICA QUE ESTA LÍNEA ESTÉ PRESENTE:
        Site::addLink("public/css/eventos.css"); 
        
        // Renderizamos la plantilla
        Renderer::render('eventos', $viewData);
    }
}