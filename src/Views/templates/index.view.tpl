
<section class="hero-modern" style="background-image: url('public/imgs/hero/heroImg.jpg');">
  <div class="hero-gradient">
    <div class="hero-inner">
      <div class="hero-logo">
        <img src="public/imgs/hero/logo.png" alt="Logo CoffeeShop">
      </div>
      <div class="hero-modern-content">
        <h1>Disfruta del mejor café de la ciudad</h1>
        <p>100% seleccionado, fresco y con el aroma perfecto para cada ocasión.</p>
      </div>
    </div>
  </div>
</section>

 <section>
  </h1><div class="product-list">
  {{foreach productos}}
  <div class="product-card" data-productId="{{productId}}">
    <div class="product-image">
      <img src="{{productImgUrl}}" alt="{{productName}}">
      <div class="price-tag">${{productPrice}}</div>
      <div class="stock-tag">Disponible {{productStock}}</div>
    </div>
    <div class="product-info">
      <h2>{{productName}}</h2>
      <p>{{productDescription}}</p>
      <form action="index.php?page=index" method="post">
        <input type="hidden" name="productId" value="{{productId}}">
        <button type="submit" name="addToCart" class="add-to-cart">
          <i class="fa-solid fa-cart-plus"></i> Agregar al Carrito
        </button>
      </form>
    </div>
  </div>
  {{endfor productos}}
</div>
 </section>

<section class="info-panel-full">
  <div class="info-card-full">
    <div class="info-card-image">
      <img src="public/imgs/hero/promotionsImg.jpg" alt="Promoción del mes">
    </div>
    <div class="info-card-content">
      <h2>Promoción del Mes</h2>
      <p>Llévate 3 por 2 en nuestras mezclas seleccionadas. Solo durante agosto en tiendas físicas.</p>
    </div>
  </div>

  <div class="info-card-full reverse">
    <div class="info-card-image">
      <img src="public/imgs/hero/concertImg.jpg" alt="Concierto en la cafetería">
    </div>
    <div class="info-card-content">
      <h2>Concierto Local</h2>
      <p>Este sábado acompáñanos en nuestra sucursal central con música acústica en vivo y el mejor café artesanal.</p>
    </div>
  </div>

  <div class="info-card-full">
    <div class="info-card-image">
      <img src="public/imgs/hero/newprod.jpg" alt="Nuevo producto">
    </div>
    <div class="info-card-content">
      <h2>Nuevo Producto</h2>
      <p>Descubre nuestro nuevo Latte Frappé, ideal para refrescarte sin perder el sabor intenso del café.</p>
    </div>
  </div>
</section>

<section class="testimonios">
  <h2 class="testimonial-title">Lo que dicen nuestros clientes</h2>
  <div class="testimonial-carousel" id="testimonial-carousel">
    <div class="testimonial active">
      <p>"El mejor café que he probado. Sabor intenso y delicioso. 😍"</p>
      <span>- Mariana R.</span>
    </div>
    <div class="testimonial">
      <p>"Entrega rápida y productos de excelente calidad. Totalmente recomendado."</p>
      <span>- José M.</span>
    </div>
    <div class="testimonial">
      <p>"Me encantó la variedad y el aroma del café. Una experiencia increíble."</p>
      <span>- Laura T.</span>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const testimonials = document.querySelectorAll(".testimonial");
    let current = 0;

    setInterval(() => {
      testimonials[current].classList.remove("active");
      current = (current + 1) % testimonials.length;
      testimonials[current].classList.add("active");
    }, 4000);
  });
</script>


