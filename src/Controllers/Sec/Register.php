<?php

namespace Controllers\Sec;

use Controllers\PublicController;
use \Utilities\Validators;
use Utilities\Site;
use Exception;

class Register extends PublicController
{
    private $txtEmail = "";
    private $txtPswd = "";
    private $errorEmail = "";
    private $errorPswd = "";
    private $hasErrors = false;

    public function run(): void
    {
        if ($this->isPostBack()) {
            $this->txtEmail = $_POST["txtEmail"];
            $this->txtPswd = $_POST["txtPswd"];

            
            if (!(Validators::IsValidEmail($this->txtEmail))) {
                $this->errorEmail = "El correo no tiene el formato adecuado";
                $this->hasErrors = true;
            }
            if (!Validators::IsValidPassword($this->txtPswd)) {
                $this->errorPswd = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un caracter especial.";
                $this->hasErrors = true;
            }

            if (!$this->hasErrors) {
                try {
                    if (\Dao\Security\Security::newUsuario($this->txtEmail, $this->txtPswd)) {
                        // ENVIAR CORREO DE BIENVENIDA
                        $this->enviarCorreoBienvenida($this->txtEmail);
                        
                        \Utilities\Site::redirectToWithMsg("index.php?page=sec_login", "¡Usuario Registrado Satisfactoriamente! Se ha enviado un correo de bienvenida.");
                    }
                } catch (Exception $ex) {
                    die($ex);
                }
            }
        }

        $viewData = get_object_vars($this); 
        Site::addLink("public/css/signin.css");
        \Views\Renderer::render("security/sigin", $viewData);
    }

    
     //Función para enviar correo de bienvenida
    private function enviarCorreoBienvenida($email) 
    {
        $asunto = "¡Bienvenido a Coffee Shop!";
        
        $mensaje = "
        <html>
        <body>
            <h2>¡Bienvenido a Coffee Shop!</h2>
            <p>Hola,</p>
            <p>Gracias por registrarte en Coffee Shop. Ahora eres parte de nuestra comunidad.</p>
            <p>¡Esperamos que disfrutes de nuestros productos!</p>
            <br>
            <p>Saludos,<br>El equipo de Coffee Shop</p>
        </body>
        </html>
        ";

        $headers = "From: helderramos533@gmail.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        @mail($email, $asunto, $mensaje, $headers);
    }
}
?>
