<link rel="stylesheet" href="public/css/resetpass.css">

<div class="resetpass-wrapper">
  <div class="resetpass-container">
    <h2 class="resetpass-title">Restablecer contraseña</h2>

    {{if showForm}}
    <form method="POST" class="reset-form">
      <label for="newPassword" class="resetpass-label">Nueva contraseña:</label>
      <input type="password" name="newPassword" id="newPassword" class="resetpass-input" required>
      <div class="resetpass-error-message-">{{errorNewPassword}}</div>

      <label for="confirmPassword" class="resetpass-label">Confirmar contraseña:</label>
      <input type="password" name="confirmPassword" id="confirmPassword" class="resetpass-input" required>
      <div class="resetpass-error-message">{{errorConfirmPassword}}</div>

      <div class="resetpass-button-container">
        <button type="submit" class="resetpass-button">Actualizar contraseña</button>
      </div>
    </form>
    {{endif showForm}}

    <div class="resetpass-success-message">
      {{if success}}✅ Tu contraseña ha sido actualizada correctamente. Ya puedes iniciar sesión.{{endif success}}
    </div>

    <div class="resetpass-error-message">{{globalError}}</div>