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
  <div>
    <p><strong>Fecha:</strong> {{fecha}}</p>
    <p><strong>Estado:</strong> {{estado}}</p>
    <p><strong>Cliente:</strong> {{nombre}}</p>
    <p><strong>Correo:</strong> {{correo}}</p>
    <p><strong>Total:</strong> {{total}}</p>
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
  <h3>Productos Comprados</h3>
  <table class="order-details">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      {{foreach productos}}
      <tr>
        <td>{{productName}}</td>
        <td>{{cantidad}}</td>
        <td>{{precio_unitario}}</td>
        <td>{{subtotal}}</td>
      </tr>
      {{endfor productos}}
    </tbody>
  </table>

</div>

  <script>
    document.addEventListener("DOMContentLoaded",()=>{
      document.getElementById("back_btn")
        .addEventListener("click", (e)=>{
          e.preventDefault();
          e.stopPropagation();
          window.location.assign("index.php?page=Client-Orders");
        });
    });
  </script>
