<div class="receipt-container">
  <div class="receipt-header">
    <h1>☕ COFFEESHOP</h1>
    <p>Gracias por tu compra</p>
  </div>

  <div class="order-info">
    <p><strong>Pedido ID:</strong> {{pedidoId}}</p>
    <p><strong>Fecha:</strong> {{fecha}}</p>
    <p><strong>Cliente:</strong> {{nombre}}</p>
    <p><strong>Email:</strong> {{correo}}</p>
  </div>

  <table class="product-table">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      {{foreach productos}}
      <tr>
        <td>{{productName}}</td>
            <td>{{cantidad}}</td>
            <td>${{precio_unitario}}</td>
            <td>${{subtotal}}</td>
      </tr>
      {{endfor productos}}
    </tbody>
  </table>

  <div class="total">
    Total Pagado: ${{total}}
  </div>

  <div class="status">
    <i class="fa-solid fa-circle-check"></i> Pago Confirmado
  </div>

  <div class="footer">
    Este recibo es solo para fines de confirmación. ¡Disfruta tu café! ☕
  </div>
</div>