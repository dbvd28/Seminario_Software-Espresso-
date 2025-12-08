<!-- Vista: Detalle/Edición de Proveedor (Administrador) -->
<div class="container">
  <button class="back-btn" id="back_btn">Atras</button>
    <h1>Proveedor {{supplierName}}</h1>

    <!-- Formulario de detalles del proveedor -->
    <form action="index.php?page=Administrator-Supplier&mode={{mode}}&id={{supplierId}}" method="post" enctype="multipart/form-data" class="details">
        
    <h2>Detalles del proveedor</h2>
    <!-- Campos principales del proveedor -->
    <div class="details-grid">
      <div>
        <label for="idsup" class="label">ID proveedor: </label>
        <input type="text" class="input" id="idsup" name="id" value="{{supplierId}}" readonly>
         <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
      </div>
      <div>
        <label for="nomsup" class="label">Nombre del proveedor: </label>
        <input type="text" class="input" id="nomsup" name="nombre" value="{{supplierName}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
      <div>
        <label for="contsup" class="label">Contacto: </label>
        <input type="text" class="input" id="contsup" name="contacto" value="{{supplierContact}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
       <div>
        <label for="telsup" class="label">Telefono del proveedor: </label>
        <input type="tel" class="input" id="telsup" name="telefono" value="{{supplierPhone}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
      <div>
        <label for="emailsup" class="label">Email proveedor: </label>
        <input type="email" class="input" id="emailsup" name="correo" value="{{supplierEmail}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
    <div>
        <label for="dirsup" class="label">Direccion del proveedor: </label>
        <input type="text" class="input" id="dirsup" name="direccion" value="{{supplierAdd}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
    </div>
     <div class="row my-2">
            <label for="estado" class="label">Estado:</label>
            <select {{if readonly}} readonly disabled {{endif readonly}} id="estado" name="estado" >
                <option value="ACT" {{selectedACT}}>Activo</option>
                <option value="INA" {{selectedINA}}>Inactivo</option>
            </select>
            {{foreach errors_estado}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_estado}}
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
                window.location.assign("index.php?page=Administrator-Suppliers");
            });
    });
</script>
