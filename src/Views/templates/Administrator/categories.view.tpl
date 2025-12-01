<div class="container">
  <div class="header">
    <h2>☕Categorias</h2>
    <a href="index.php?page=Administrator-Category&mode=INS" class="btn-new"><i class="fa-solid fa-plus"></i> Nueva Categoria</a>
  </div>

  <div class="grid">
    {{foreach categorias}}
    <div class="card">
      <div class="card-header">
        <div>
          <h3>{{nombre}}</h3>
          <p>{{descripcion}}</p>
        </div>
        <div class="actions">
          <a href="index.php?page=Administrator-Category&mode=UPD&id={{categoriaId}}" class="btn-edit"><i class="fa-solid fa-pen"></i>  Editar</a>
        </div>
      </div>
      <span class="status {{estadoClase}}">{{estadoTexto}}
      </span>
    </div>
    {{endfor categorias}}
  </div>

