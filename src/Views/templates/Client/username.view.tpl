<div class="form-container">
  <h2>Actualizar nombre de usuario</h2>
  <form method="POST" action="index.php?page=Client-user&mode=UPD&id={{id}}">
    <label for="username">Ingresa tu nuevo nombre de usuario</label>
    <input 
      type="text" 
      id="username" 
      name="username" 
      value="{{username}}" 
      required 
      placeholder="Nuevo nombre" 
    />
     <input type="hidden" name="id" value="{{id}}" />
     <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
    <button type="submit">Guardar nombre</button>
  </form>
</div>
