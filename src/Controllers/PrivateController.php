<?php
/**
 * PHP Version 7.2
 *
 * @category Private
 * @package  Controllers
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  MIT http://
 * @version  CVS:1.0.0
 * @link     http://
 */
namespace Controllers;

/**
 * Private Access Controller Base Class
 *
 * Clase abstracta que proporciona control de acceso para controladores privados.
 * Verifica autenticación y autorización antes de permitir acceso a recursos protegidos.
 *
 * @category Public
 * @package  Controllers
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  MIT http://
 * @link     http://
 */
abstract class PrivateController extends PublicController
{
    /**
     * Verifica si el usuario tiene autorización para acceder al controlador
     *
     * Valida que el usuario autenticado tenga permisos explícitos para acceder
     * al controlador actual, excepto para controladores en la lista blanca que
     * solo requieren autenticación.
     *
     * @return void
     * @throws PrivateNoAuthException Si el usuario no está autorizado
     */
    private function _isAuthorized()
    {
        // Lista blanca de controladores que solo requieren autenticación
        $whitelist = [
            'Controllers\\Client\\Password',
            'Controllers\\Client\\User',
        ];
        
        // Permite acceso a controladores en la lista blanca sin validación adicional
        if (in_array($this->name, $whitelist, true)) {
            return;
        }
        
        // Valida que el usuario esté autorizado para acceder a este controlador
        $isAuthorized = \Utilities\Security::isAuthorized(
            \Utilities\Security::getUserId(),
            $this->name,
            'CTR'
        );
        
        // Lanza excepción si el usuario no está autorizado
        if (!$isAuthorized){
            throw new PrivateNoAuthException();
        }
    }
    
    /**
     * Verifica si el usuario está autenticado en la aplicación
     *
     * Valida que exista una sesión activa del usuario. Si no está autenticado,
     * lanza una excepción indicando que no hay sesión iniciada.
     *
     * @return void
     * @throws PrivateNoLoggedException Si el usuario no está autenticado
     */
    private function _isAuthenticated()
    {
        if (!\Utilities\Security::isLogged()){
            throw new PrivateNoLoggedException();
        }
    }
    
    /**
     * Verifica si el usuario tiene autorización para una funcionalidad específica
     *
     * Valida que el usuario autenticado posea permisos para acceder a una
     * característica o funcionalidad específica del sistema.
     *
     * @param string $feature Nombre de la característica a validar
     * @return bool True si está autorizado, False en caso contrario
     */
    protected function isFeatureAutorized($feature) :bool
    {
        return \Utilities\Security::isAuthorized(
            \Utilities\Security::getUserId(),
            $feature
        );
    }
    
    /**
     * Constructor del controlador privado
     *
     * Ejecuta las validaciones de autenticación y autorización al instanciar
     * la clase. Primero verifica que el usuario esté autenticado, luego que
     * tenga permisos para acceder al controlador.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->_isAuthenticated();
        $this->_isAuthorized();
    }
}
