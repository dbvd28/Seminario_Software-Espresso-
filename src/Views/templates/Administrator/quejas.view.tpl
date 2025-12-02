
<div class="quejas-container">
  <div class="quejas-header">
    <h2>Gestión de Quejas y Sugerencias</h2>
  </div>

  <!-- Tabla de quejas -->
  <table class="quejas-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Email</th>
        <th>Descripción</th>
        
        <th>Fecha</th>
        
      </tr>
    </thead>
    <tbody>
      {{foreach quejas}}
      <tr>
        <td>{{quejaId}}</td>
        <td>{{username}}</td>
        <td>{{useremail}}</td>
        <td>{{descripcion}}</td>
        <td>{{fecha}}</td>
        
        <td>
          {{if respuesta}}
            {{respuesta}}
          {{else}}
            <span class="pendiente">Sin respuesta</span>
          {{endif}}
        </td>
        
      </tr>
      {{endfor quejas}}
    </tbody>
  </table>
</div>
