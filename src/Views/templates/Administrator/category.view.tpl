<!-- Vista: Detalle/Edición de Categoría (Administrador) -->
<div class="container">
  <button class="back-btn" id="back_btn">Atras</button>
    <h1>Categoria #{{categoryName}}</h1>

    <!-- Formulario de detalles de categoría -->
    <form action="index.php?page=Administrator-Categories&mode={{mode}}&id={{categoryId}}" method="post" enctype="multipart/form-data" class="details">
        
    <h2>Detalles de la categoria</h2>
    <div class="details-grid">
      <div>
        <label for="idcat" class="label">ID Categoria: </label>
        <input type="text" class="input" id="idcat" name="id" value="{{categoryId}}" readonly>
         <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
      </div>
      <div>
        <label for="nomcat" class="label">Nombre Producto: </label>
        <input type="text" class="input" id="nomcat" name="nombre" value="{{categoryName}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
      <div>
        <label for="dscpcat" class="label">Descripción: </label>
        <input type="text" class="input" id="dscpcat" name="descripcion" value="{{categoryDescription}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
    </div>
    <div class="actions">
      <button type="submit" class="btn_submit" name="btnEnviar"{{if readonly}} hidden {{endif readonly}}>Guardar cambios</button>
    </div>
  </form>
</div>
   <script>
    document.addEventListener("DOMContentLoaded", ()=>{
        document.getElementById("back_btn")
            .addEventListener("click", (e)=>{
                e.preventDefault();
                e.stopPropagation();
                window.location.assign("index.php?page=Administrator-Categories");
            });
    });
</script>
