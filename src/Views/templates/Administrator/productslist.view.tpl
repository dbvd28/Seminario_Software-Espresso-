<div class="container">
  <h1>Productos</h1>
  <a href="index.php?page=Administrator-Products&mode=INS" class="btn new">Nuevo Producto</a>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Estado</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {{foreach productos}}
      <tr>
        <td>{{productId}}</td>
        <td>{{productName}}</td>
        <td>{{productPrice}}</td>
        <td>{{productStock}}</td>
        <td>{{productStatus}}</td>
        <td>
          <a href="index.php?page=Administrator-Products&mode=UPD&id={{productId}}" class="btn edit">Editar</a>
        </td>
      </tr>
      {{endfor productos}}
    </tbody>
  </table>
</div>
