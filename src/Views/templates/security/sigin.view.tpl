<section class="register-wrapper">
  <div class="register-container">
    <div class="register-image">
      <img src="public/imgs/hero/register.jpg" alt="Coffee Art" />
    </div>
    <form class="register-form" method="post" action="index.php?page=sec_register">
      <h2>Crear tu cuenta</h2>

      <label for="txtEmail">Correo Electrónico</label>
      <input type="email" id="txtEmail" name="txtEmail" value="{{txtEmail}}" placeholder="tucorreo@ejemplo.com" />
      {{if errorEmail}}
        <div class="error">{{errorEmail}}</div>
      {{endif errorEmail}}

      <label for="txtPswd">Contraseña</label>
      <input type="password" id="txtPswd" name="txtPswd" value="{{txtPswd}}" placeholder="Crea una contraseña segura" />
      {{if errorPswd}}
        <div class="error">{{errorPswd}}</div>
      {{endif errorPswd}}

      <div class="btn-container">
        <button class="btn-register" id="btnSignin" type="submit">Crear Cuenta</button>
      </div>
    </form>
  </div>
</section>