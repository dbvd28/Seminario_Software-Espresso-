<section class="login-wrapper">
  <div class="login-container">
    
    <div class="login-image">
      <img src="public/imgs/hero/coffeeshop.jpg" alt="Coffee Shop" />
    </div>

    <form class="login-form" method="post" action="index.php?page=sec_login{{if redirto}}&redirto={{redirto}}{{endif redirto}}">
      <h2>Iniciar Sesión</h2>

      <label for="txtEmail">Correo Electrónico</label>
      <input type="email" id="txtEmail" name="txtEmail" value="{{txtEmail}}" placeholder="tucorreo@ejemplo.com" />
      {{if errorEmail}}
        <div class="error">{{errorEmail}}</div>
      {{endif errorEmail}}

      <label for="txtPswd">Contraseña</label>
      <input type="password" id="txtPswd" name="txtPswd" value="{{txtPswd}}" placeholder="••••••••" />
      {{if errorPswd}}
        <div class="error">{{errorPswd}}</div>
      {{endif errorPswd}}

      {{if generalError}}
        <div class="error general-error">{{generalError}}</div>
      {{endif generalError}}

      <div class="btn-container">
        <button class="btn-login" id="btnLogin" type="submit">Iniciar Sesión</button>
      </div>
    </form>
  </div>
</section>