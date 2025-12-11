<section class="container-l">
  <section class="depth-4 page-title">
    <h1>🛒 Checkout</h1>
  </section>

  <section class="grid checkout-box">
    <!-- Header row -->
    <div class="row border-b header-row">
      <span class="col-1">#</span>
      <span class="col-4">Producto</span>
      <span class="col-2 right">Precio</span>
      <span class="col-3 center">Cantidad</span>
      <span class="col-2 right">Subtotal</span>
    </div>

    <!-- Productos -->
    {{foreach carretilla}}
    <div class="row border-b">
      <span class="col-1" data-label="N°">{{row}}</span>
      <span class="col-4" data-label="Producto">{{productName}}</span>
      <span class="col-2 right" data-label="Precio">L {{crrprc}}</span>

      <span class="col-3 center">
        <form action="index.php?page=Checkout_Checkout" method="post" class="qty-form">
          <input type="hidden" name="productId" value="{{productId}}" />

          <button type="submit" name="removeOne" class="circle btn-minus">
            <i class="fas fa-minus"></i>
          </button>

          <span class="qty">{{crrctd}}</span>

          <button type="submit" name="addOne" class="circle btn-plus">
            <i class="fas fa-plus"></i>
          </button>
        </form>
      </span>

      <span class="col-2 right" data-label="Subtotal">L {{subtotal}}</span>
    </div>
    {{endfor carretilla}}

    <!-- Total -->
    <div class="row total-row">
      <span class="col-3 offset-7 center">Total:</span>
      <span class="col-2 right">L {{total}}</span>
    </div>

    <!-- Confirm order -->
    <div class="row place-order">
      <form action="index.php?page=Checkout_Checkout" method="post" class="col-12 right">
        <button type="submit" class="btn-confirm">
          🧾 Confirmar Pedido
        </button>
      </form>
    </div>
  </section>
</section>