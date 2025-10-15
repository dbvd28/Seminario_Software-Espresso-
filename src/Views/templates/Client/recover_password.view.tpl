<h2>¿Olvidaste tu contraseña?</h2>

<form method="POST" class="recover-form">
  <label for="email">Correo electrónico:</label>
  <input type="email" name="email" id="email" value="{{email}}" required>

  <div class="error-message">{{errorEmail}}</div>

  <button type="submit">Enviar enlace</button>
</form>

<div class="success-message">{{successMessage}}</div>
<div class="error-message">{{globalError}}</div>