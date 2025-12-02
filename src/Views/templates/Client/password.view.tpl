<div class="form-container">
  <h2>Cambiar Contraseña</h2>
  <form method="POST" action="index.php?page=Client-Password">
    <label for="oldPassword">Contraseña Actual</label>
    <input 
      type="password" 
      id="oldPassword" 
      name="oldPassword" 
      required 
      placeholder="Ingrese su contraseña actual" 
    />
    {{if errors_oldPassword}}
      <div class="error">{{foreach errors_oldPassword}}<p>{{this}}</p>{{endfor errors_oldPassword}}</div>
    {{endif errors_oldPassword}}
    
    <label for="newPassword">Nueva Contraseña</label>
    <input 
      type="password" 
      id="newPassword" 
      name="newPassword" 
      required 
      placeholder="Ingrese su nueva contraseña" 
    />
    {{if errors_newPassword}}
      <div class="error">{{foreach errors_newPassword}}<p>{{this}}</p>{{endfor errors_newPassword}}</div>
    {{endif errors_newPassword}}
    
    <label for="confirmPassword">Confirmar Nueva Contraseña</label>
    <input 
      type="password" 
      id="confirmPassword" 
      name="confirmPassword" 
      required 
      placeholder="Confirme su nueva contraseña" 
    />
    {{if errors_confirmPassword}}
      <div class="error">{{foreach errors_confirmPassword}}<p>{{this}}</p>{{endfor errors_confirmPassword}}</div>
    {{endif errors_confirmPassword}}
    
    {{if errors_global}}
      <div class="error general-error">{{foreach errors_global}}<p>{{this}}</p>{{endfor errors_global}}</div>
    {{endif errors_global}}
    
    <input type="hidden" name="id" value="{{id}}" />
    <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
    <button type="submit">Cambiar Contraseña</button>
  </form>
</div>