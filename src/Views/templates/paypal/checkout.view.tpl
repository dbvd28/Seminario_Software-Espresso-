<section class="container-l" style="padding: 2rem; max-width: 960px; margin: auto;">
  <section class="depth-4" style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: bold; text-align: center;">🛒 Checkout</h1>
  </section>

  <section class="grid" style="border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background: #fff;">
    <!-- Header row -->
    <div class="row border-b" style="background: #f5f5f5; padding: 0.75rem 1rem; font-weight: bold; display: flex; align-items: center;">
      <span class="col-1">#</span>
      <span class="col-4">Producto</span>
      <span class="col-2 right">Precio</span>
      <span class="col-3 center">Cantidad</span>
      <span class="col-2 right">Subtotal</span>
    </div>

    <!-- Product rows -->
    {{foreach carretilla}}
    <div class="row border-b" style="padding: 0.75rem 1rem; display: flex; align-items: center; border-bottom: 1px solid #eee;">
      <span class="col-1">{{row}}</span>
      <span class="col-4">{{productName}}</span>
      <span class="col-2 right">L {{crrprc}}</span>
      <span class="col-3 center">
        <form action="index.php?page=checkout_checkout" method="post" style="display: inline-flex; align-items: center; gap: 0.5rem;">
          <input type="hidden" name="productId" value="{{productId}}" />
          <button type="submit" name="removeOne" class="circle" style="background: #f44336; color: white; border: none; padding: 0.4rem 0.6rem; border-radius: 50%;">
            <i class="fa-solid fa-minus"></i>
          </button>
          <span style="min-width: 2rem; text-align: center;">{{crrctd}}</span>
          <button type="submit" name="addOne" class="circle" style="background: #4caf50; color: white; border: none; padding: 0.4rem 0.6rem; border-radius: 50%;">
            <i class="fa-solid fa-plus"></i>
          </button>
        </form>
      </span>
      <span class="col-2 right">L {{subtotal}}</span>
    </div>
    {{endfor carretilla}}

    <!-- Total row -->
    <div class="row" style="padding: 1rem; font-weight: bold; font-size: 1.1rem; display: flex; align-items: center;">
      <span class="col-3 offset-7 center">Total:</span>
      <span class="col-2 right">L {{total}}</span>
    </div>

    <!-- Place order -->
    <div class="row" style="padding: 1rem; display: flex; justify-content: flex-end;">
      <form action="index.php?page=checkout_checkout" method="post" class="col-12 right" style="text-align: right;">
        <button type="submit" style="padding: 0.75rem 1.5rem; background: #2196f3; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer;">
          🧾 Confirmar Pedido
        </button>
      </form>
    </div>
  </section>
</section>
