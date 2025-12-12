<?php

namespace Controllers\Sec;

use Controllers\PublicController;
use \Utilities\Validators;
use Utilities\Site;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
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

            if (!(Validators::IsValidEmail($this->txtEmail))) {
                $this->errorEmail = "El correo no tiene el formato adecuado";
                $this->hasErrors = true;
            }
            if (!Validators::IsValidPassword($this->txtPswd)) {
                $this->errorPswd = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un caracter especial.";
                $this->hasErrors = true;
            }
            if (Validators::EmailExist($this->txtEmail)) {
                $this->errorEmail = "Este correo ya esta registrado, porfavor ingrese uno diferente.";
                $this->hasErrors = true;
            }

            if (!$this->hasErrors) {
                try {
                    if (\Dao\Security\Security::newUsuario($this->txtEmail, $this->txtPswd, $this->txtName)) {
                        // ENVIAR CORREO DE BIENVENIDA
                        $this->enviarCorreoBienvenida($this->txtEmail);

                        \Utilities\Site::redirectToWithMsg("index.php?page=Sec_Login", "¡Usuario Registrado Satisfactoriamente! Se ha enviado un correo de bienvenida.");
                    }
                } catch (Exception $ex) {
                    die($ex);
                }
            }
        }

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
            <h2>¡Bienvenido a Coffee Shop!</h2>
            <p>Hola,</p>
            <p>Gracias por registrarte en Coffee Shop. Ahora eres parte de nuestra comunidad.</p>
            <p>¡Esperamos que disfrutes de nuestros productos!</p>
            <br>
            <p>Saludos,<br>El equipo de Coffee Shop</p>
        ';

            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
        }
    }
}
?>
