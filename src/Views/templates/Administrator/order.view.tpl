<div class="container">
    <button class="back-btn" id="back_btn">Atras</button>
    <h1>Pedido #{{id}}</h1>
  <div class="order-progress">
    <div class="progress-step {{if step1}}active{{endif step1}}">
      <div class="dot">1</div>
      <div class="label">Pendiente</div>
    </div>
    <div class="progress-line {{if step2}}active{{endif step2}}"></div>
    <div class="progress-step {{if step2}}active{{endif step2}}">
      <div class="dot">2</div>
      <div class="label">Aceptado</div>
    </div>
    <div class="progress-line {{if step3}}active{{endif step3}}"></div>
    <div class="progress-step {{if step3}}active{{endif step3}}">
      <div class="dot">3</div>
      <div class="label">Enviado</div>
    </div>
  </div>
  {{if showShipping}}
  <div class="shipping-anim">
    <div class="shipping-anim__sky">
      <div class="shipping-anim__cloud c1"></div>
      <div class="shipping-anim__cloud c2"></div>
    </div>
    <div class="shipping-anim__road">
      <div class="shipping-anim__lane"></div>
    </div>
    <div class="shipping-anim__truck">
      <div class="truck__cabin"></div>
      <div class="truck__box"></div>
      <div class="truck__wheel w1"></div>
      <div class="truck__wheel w2"></div>
    </div>
  </div>
  {{endif showShipping}}

    <form action="index.php?page=Administrator-Order&mode={{mode}}&id={{id}}" method="post" class="details">
        
    <h2>Detalles del pedido</h2>
    <div class="details-grid">
      <div>
        <label for="idped" class="label">ID Pedido: </label>
        <input type="text" class="input" id="idped" name="id" value="{{id}}" readonly>
         <input type="hidden" name="xsrtoken" value="{{xsrtoken}}" />
      </div>
      <div>
        <label for="fcped" class="label">Fecha pedido: </label>
        <input type="text" class="input" id="fcped" name="date" value="{{fecha}}" readonly>
      </div>
      <div>
        <label for="clped" class="label">Nombre del cliente: </label>
        <input type="text" class="input" id="clped" name="client" value="{{nombre}}" readonly>
      </div>
      <div>
       <label for="emped" class="label">Correo del cliente: </label>
        <input type="text" class="input" id="emped" name="email" value="{{correo}}" readonly>
      </div>
      <div>
        <label for="totped" class="label">Total a pagar: </label>
        <input type="text" class="input" id="totped" name="total" value="{{total}}" readonly>
      </div>
      <div class="row my-2">
            <label for="estado" class="label">Estado:</label>
            <select {{if readonly}} readonly disabled {{endif readonly}} id="estado" name="status" >
                <option value="PEND" {{selectedPEND}}>Pendiente</option>
                <option value="PAG" {{selectedPAG}}>Aceptado</option>
                <option value="ENV" {{selectedENV}}>Enviado</option>
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
    <h2>Productos comprados</h2>
    <table>
      <thead>
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio</th>
          <th colspan="2">Total</th>
        </tr>
      </thead>
      <tbody>
        {{foreach productos}}
         <tr>
            <td>{{productName}}</td>
            <td>{{cantidad}}</td>
            <td>{{precio_unitario}}</td>
            <td colspan="2">{{subtotal}}</td>
         </tr>
        {{endfor productos}}
      </tbody>
      <tfoot>
  <tr>
    <td colspan="2" style="text-align:right; font-weight:bold;">Total general:</td>
    <td>{{total}}</td>
  </tr>
</tfoot>
    </table>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", ()=>{
        document.getElementById("back_btn")
            .addEventListener("click", (e)=>{
                e.preventDefault();
                e.stopPropagation();
                window.location.assign("index.php?page=Administrator-Orders");
            });
    });
</script>