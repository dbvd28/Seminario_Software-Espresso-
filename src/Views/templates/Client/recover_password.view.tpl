<link rel="stylesheet" href="public/css/recoverpass.css">

<section class="recover-wrapper">
  <div class="recover-container">

    <div class="recover-image">
      <img src="public/imgs/hero/coffeeshop.jpg" alt="Coffee Shop" />
    </div>

    <h2 class="recover-title">¿Olvidaste tu contraseña?</h2>

    <form method="POST" class="recover-form">
      <label for="email" class="recover-label">Correo electrónico:</label>
      <input type="email" name="email" id="email" class="recover-input" value="{{email}}" required>

      <div class="recover-error-email  error-message">{{errorEmail}}</div>

    <div class="container-button" style="display: flex; justify-content: center; width: 100%; border: 2px;">
      <button type="submit" class="recover-button">Enviar enlace</button>
    </div>
    </form>

    <div class="recover-success-message success-message">{{successMessage}}</div>
    <div class="recover-global-error error-message">{{globalError}}</div>
  </div>
</section>
