<?php

namespace Controllers\Sec;

use Controllers\PublicController;
use \Utilities\Validators;
use Utilities\Site;
use Exception;
<<<<<<< HEAD
use PHPMailer\PHPMailer\PHPMailer;
=======

>>>>>>> 4b948e4e086e285ce434b30daa28f700dffb1d71
class Register extends PublicController
{
    private $txtName = "";
    private $txtEmail = "";
    private $txtPswd = "";
    private $errorEmail = "";
    private $errorPswd = "";
    private $hasErrors = false;

    public function run(): void
    {
        if ($this->isPostBack()) {
            $this->txtName = $_POST["username"];
            $this->txtEmail = $_POST["txtEmail"];
            $this->txtPswd = $_POST["txtPswd"];

<<<<<<< HEAD

=======
            
>>>>>>> 4b948e4e086e285ce434b30daa28f700dffb1d71
            if (!(Validators::IsValidEmail($this->txtEmail))) {
                $this->errorEmail = "El correo no tiene el formato adecuado";
                $this->hasErrors = true;
            }
            if (!Validators::IsValidPassword($this->txtPswd)) {
                $this->errorPswd = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un caracter especial.";
                $this->hasErrors = true;
            }
<<<<<<< HEAD
            if (Validators::EmailExist($this->txtEmail)) {
                $this->errorEmail = "Este correo ya esta registrado, porfavor ingrese uno diferente.";
                $this->hasErrors = true;
            }
=======
>>>>>>> 4b948e4e086e285ce434b30daa28f700dffb1d71

            if (!$this->hasErrors) {
                try {
                    if (\Dao\Security\Security::newUsuario($this->txtEmail, $this->txtPswd, $this->txtName)) {
                        // ENVIAR CORREO DE BIENVENIDA
                        $this->enviarCorreoBienvenida($this->txtEmail);
<<<<<<< HEAD

                        \Utilities\Site::redirectToWithMsg("index.php?page=Sec_Login", "¡Usuario Registrado Satisfactoriamente! Se ha enviado un correo de bienvenida.");
=======
                        
                        \Utilities\Site::redirectToWithMsg("index.php?page=sec_login", "¡Usuario Registrado Satisfactoriamente! Se ha enviado un correo de bienvenida.");
>>>>>>> 4b948e4e086e285ce434b30daa28f700dffb1d71
                    }
                } catch (Exception $ex) {
                    die($ex);
                }
            }
        }

<<<<<<< HEAD
        $viewData = get_object_vars($this);
        Site::addLink("public/css/signin-copy.css");
        \Views\Renderer::render("security/sigin", $viewData);
    }


    //Función para enviar correo de bienvenida
    private function enviarCorreoBienvenida($email)
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP config for AlwaysData
            $mail->isSMTP();
            $mail->Host = 'smtp-dvarela.alwaysdata.net';
            $mail->SMTPAuth = true;
            $mail->Username = 'dvarela@alwaysdata.net'; // your mailbox
            $mail->Password = 'Mosesdagama2&';             // password you created
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;  

            // Sender and recipient
            $mail->setFrom('dvarela@alwaysdata.net', 'Coffee Shop');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = '¡Bienvenido a Coffee Shop!';
            $mail->Body = '
=======
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
>>>>>>> 4b948e4e086e285ce434b30daa28f700dffb1d71
            <h2>¡Bienvenido a Coffee Shop!</h2>
            <p>Hola,</p>
            <p>Gracias por registrarte en Coffee Shop. Ahora eres parte de nuestra comunidad.</p>
            <p>¡Esperamos que disfrutes de nuestros productos!</p>
            <br>
            <p>Saludos,<br>El equipo de Coffee Shop</p>
<<<<<<< HEAD
        ';

            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
        }
    }
}
?>
=======
        </body>
        </html>
        ";

        $headers = "From: helderramos533@gmail.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        @mail($email, $asunto, $mensaje, $headers);
    }
}
?>
>>>>>>> 4b948e4e086e285ce434b30daa28f700dffb1d71
