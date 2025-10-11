<link rel="stylesheet" href="public/css/quejas.css">

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
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal para ver respuesta completa -->
<div class="modal fade" id="respuestaModal" tabindex="-1" aria-labelledby="respuestaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="respuestaModalLabel">Respuesta Completa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="respuestaCompleta">
      </div>
      <div class="modal-footer">
        <a href="index.php" class="btn-regresar">Regresar</a>
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