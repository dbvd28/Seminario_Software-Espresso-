<link rel="stylesheet" href="/proyecto/Seminario_Software-Espresso-/public/css/quejasuser.css">

<div class="quejas-container">
  <div class="quejas-header">
    <h2>Gestión de Quejas y Sugerencias</h2>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="quejas-form">
        <h3>Enviar Nueva Queja o Sugerencia</h3>
        
        {{if error}}
        <div class="error">
          {{errorMsg}}
        </div>
        {{endif error}}
        
        {{if success}}
        <div style="color: #155724; background-color: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
          {{successMsg}}
        </div>
        {{endif success}}
        
        <form method="post" action="index.php?page=Client_Quejas">
          <div class="form-group">
            <label for="asunto">Asunto</label>
            <input type="text" id="asunto" name="asunto" value="{{asunto}}" required>
          </div>
          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="5" required>{{descripcion}}</textarea>
          </div>
          <div class="button-group">
            <button type="submit" name="btnEnviar">Enviar</button>
            
          </div>
          <div class="button-group">
          <a href="index.php" class="btn-regresar">Regresar</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
function verRespuesta(respuesta) {
  document.getElementById('respuestaCompleta').innerText = respuesta;
  var modal = new bootstrap.Modal(document.getElementById('respuestaModal'));
  modal.show();
}