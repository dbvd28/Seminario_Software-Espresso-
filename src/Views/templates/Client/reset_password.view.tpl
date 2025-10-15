<h2>Restablecer contraseña</h2>

{{if showForm}}
<form method="POST" class="reset-form">
  <label for="newPassword">Nueva contraseña:</label>
  <input type="password" name="newPassword" id="newPassword" required>
  <div class="error-message">{{errorNewPassword}}</div>

  <label for="confirmPassword">Confirmar contraseña:</label>
  <input type="password" name="confirmPassword" id="confirmPassword" required>
  <div class="error-message">{{errorConfirmPassword}}</div>

  <button type="submit">Actualizar contraseña</button>
</form>
{{endif showForm}}

<div class="success-message">
  {{if success}}✅ Tu contraseña ha sido actualizada correctamente. Ya puedes iniciar sesión.{{endif success}}
</div>

<div class="error-message">{{globalError}}</div>