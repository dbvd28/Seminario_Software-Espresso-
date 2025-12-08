<?php
namespace Controllers\Sec;
class Logout extends \Controllers\PublicController
{
<<<<<<< HEAD
    public function run(): void
=======
    public function run():void
>>>>>>> 4b948e4e086e285ce434b30daa28f700dffb1d71
    {
        \Utilities\Security::logout();
        \Utilities\Site::redirectTo("index.php");
    }
}

<<<<<<< HEAD
?>
=======
?>
>>>>>>> 4b948e4e086e285ce434b30daa28f700dffb1d71
