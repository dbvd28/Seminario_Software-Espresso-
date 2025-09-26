<div class="container">
    <button class="back-btn" id="back_btn">Atras</button>
    <h1>Producto #{{productId}}</h1>

    <form action="index.php?page=Administrator-Products&mode={{mode}}&id={{productId}}" method="post" class="details">
        
    <h2>Detalles del producto</h2>
    <div class="details-grid">
      <div>
        <label for="idprod" class="label">ID Producto: </label>
        <input type="text" class="input" id="idprod" name="id" value="{{productId}}" readonly>
         <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
      </div>
      <div>
        <label for="nomprod" class="label">Nombre Producto: </label>
        <input type="text" class="input" id="nomprod" name="nombre" value="{{productName}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
      <div>
        <label for="dscprod" class="label">Descripción: </label>
        <input type="text" class="input" id="dscprod" name="descripcion" value="{{productDescription}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
      <div>
       <label for="prcprod" class="label">Precio: </label>
        <input type="text" class="input" id="prcprod" name="precio" value="{{productPrice}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
      <div>
        <label for="stprod" class="label">Cantidad Disponible: </label>
        <input type="text" class="input" id="stprod" name="stock" value="{{productStock}}" {{if readonly}} readonly disabled {{endif readonly}}>
      </div>
      <div class="row my-2">
            <label for="estado" class="label">Estado:</label>
            <select {{if readonly}} readonly disabled {{endif readonly}} id="estado" name="status" >
                <option value="ACT" {{selectedACT}}>Activo</option>
                <option value="INA" {{selectedINA}}>Inactivo</option>
            </select>
            {{foreach errors_estado}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_estado}}
        </div>
        <div class="row my-2">
            <label for="proveedor" class="label">Proveedor:</label>
            <select {{if readonly}} readonly disabled {{endif readonly}} id="proveedor" name="prov" >
               {{foreach proveedor}}
               <option value="{{proveedorId}}" {{selectedidp}}>{{nombre}}</option>
               {{endfor proveedor}}
            </select>
            {{foreach errors_estado}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_estado}}
        </div>
         <div class="row my-2">
            <label for="categoria" class="label">Categoria:</label>
            <select {{if readonly}} readonly disabled {{endif readonly}} id="categoria" name="cat" >
               {{foreach categoria}}
               <option value="{{categoriaId}}" {{selectedidc}}>{{nombre}}</option>
               {{endfor categoria}}
            </select>
            {{foreach errors_estado}}
                <div class="error col-12">{{this}}</div>
             {{endfor errors_estado}}
        </div>
    </div>
    <div class="actions">
 <button type="submit" class="btn_submit" name="btnEnviar"{{if readonly}} hidden {{endif readonly}}>Guardar cambios</button>
    </div>
  </form>
   <script>
    document.addEventListener("DOMContentLoaded", ()=>{
        document.getElementById("back_btn")
            .addEventListener("click", (e)=>{
                e.preventDefault();
                e.stopPropagation();
                window.location.assign("index.php?page=Administrator-Productslist");
            });
    });
</script>