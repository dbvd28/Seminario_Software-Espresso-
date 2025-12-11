<!DOCTYPE html>
<html>
<head>
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background: #f1f1f1;
        line-height: 1.4;
    }

    .container-l {
        width: 100%;
        max-width: 100%;
        padding: 1rem;
    }

    .grid {
        width: 100%;
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        padding: 0.75rem 1rem;
        align-items: center;
    }

    .border-b {
        border-bottom: 1px solid #e3e3e3;
    }

    .col-1 { width: 8%; }
    .col-2 { width: 16%; }
    .col-3 { width: 25%; }
    .col-4 { width: 34%; }
    .col-12 { width: 100%; }

    .right { text-align: right; }
    .center { text-align: center; }

    

    .circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: transform 0.2s;
    }

    .circle:active {
        transform: scale(0.92);
    }

    /* ======== RESPONSIVE ======== */
    @media (max-width: 768px) {
        .container-l {
            padding: 0.5rem;
        }

        /* Ocultar encabezado en móviles */
        .grid .row:first-child {
            display: none;
        }

        /* Estilo de tarjeta para cada producto */
        .grid .row {
            flex-direction: column;
            align-items: stretch;
            padding: 1rem;
            margin: 0.5rem 0;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Ajustar columnas en móviles */
        .col-1, .col-2, .col-3, .col-4 {
            width: 100% !important;
            padding: 0.25rem 0;
            display: flex;
            justify-content: space-between;
        }

        /* Estilo para las etiquetas en móviles */
        .grid .row > span::before {
            content: attr(data-label);
            font-weight: 600;
            color: #666;
            margin-right: 1rem;
        }

        /* Controles de cantidad */
        .grid .row .col-3 {
            justify-content: center;
            gap: 1rem;
            padding: 0.75rem 0;
            margin: 0.5rem 0;
            border-top: 1px solid #f0f0f0;
            border-bottom: 1px solid #f0f0f0;
        }

        .grid .row .col-3::before {
            display: none; /* No mostrar etiqueta en controles */
        }

        /* Botón de confirmar pedido */
        form[action*="Checkout"] {
            margin-top: 1.5rem;
        }

        form[action*="Checkout"] button {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            background: #2196f3;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Total */
        .grid .row:last-child {
            background: #f8f9fa;
            border-radius: 8px;
            margin-top: 1rem;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .grid .row:last-child .col-2 {
            text-align: right;
        }
    }

    /* Ajustes para pantallas muy pequeñas */
    @media (max-width: 360px) {
        .circle {
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
        }
        
        .grid .row {
            padding: 0.75rem;
        }
    }
</style>
</head>
<body>
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

    <!-- En la sección de productos -->
{{foreach carretilla}}
<div class="row border-b">
    <span class="col-1" data-label="N°">{{row}}</span>
    <span class="col-4" data-label="Producto">{{productName}}</span>
    <span class="col-2 right" data-label="Precio">L {{crrprc}}</span>
    <span class="col-3 center">
        <form action="index.php?page=Checkout_Checkout" method="post" style="display: inline-flex; align-items: center; gap: 0.5rem;">
            <input type="hidden" name="productId" value="{{productId}}" />
            <button type="submit" name="removeOne" class="circle" style="background: #f44336; color: white;">
                <i class="fas fa-minus"></i>
            </button>
            <span style="min-width: 2rem; text-align: center;">{{crrctd}}</span>
            <button type="submit" name="addOne" class="circle" style="background: #4caf50; color: white;">
                <i class="fas fa-plus"></i>
            </button>
        </form>
    </span>
    <span class="col-2 right" data-label="Subtotal">L {{subtotal}}</span>
</div>
{{endfor carretilla}}

<!-- En la fila del total -->
<div class="row">
    <span class="col-3 offset-7 center">Total:</span>
    <span class="col-2 right">L {{total}}</span>
</div>

    <!-- Place order -->
    <div class="row" style="padding: 1rem; display: flex; justify-content: flex-end;">
      <form action="index.php?page=Checkout_Checkout" method="post" class="col-12 right" style="text-align: right;">
        <button type="submit" style="padding: 0.75rem 1.5rem; background: #2196f3; color: white; border: none; border-radius: 5px; font-size: 1rem; cursor: pointer;">
          🧾 Confirmar Pedido
        </button>
      </form>
    </div>
  </section>
</section>

</body>
</html>

