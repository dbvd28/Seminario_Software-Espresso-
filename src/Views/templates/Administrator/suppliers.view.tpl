<div class="container">
  <div class="header">
    <h2>☕ Proveedores</h2>
    <a href="index.php?page=Administrator-Supplier&mode=INS" class="btn-new">
      <i class="fa-solid fa-plus"></i> Nuevo Proveedor
    </a>
  </div>

  <div class="grid">
    {{foreach proveedores}}
    <div class="card">
      <div class="card-body">
        <div class="card-info">
          <h3>{{nombre}}</h3>
          <p><strong>{{contacto}}</strong></p>
          <p class="email">{{email}}</p>
        </div>
        <div class="actions">
          <a href="index.php?page=Administrator-Supplier&mode=UPD&id={{proveedorId}}" class="btn-edit">
            <i class="fa-solid fa-pen"></i> Editar
          </a>
        </div>
      </div>
       <span class="status {{estadoClase}}">{{estadoTexto}}
    </div>
    {{endfor proveedores}}
  </div>
</div>