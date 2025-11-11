<style>

.carousel-slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center center;
    opacity: 0;
    transition: opacity 1.5s ease-in-out;
}

.carousel-slide.active {
    opacity: 1;
}


.categoria-titulo {
    font-size: 2em;
    color: #333;
    margin-top: 40px;
    margin-bottom: 20px;
    text-align: center;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
}

</style>

<section class="hero-modern" id="hero-carousel">
    <div class="carousel-slide active" style="background-image: url('public/imgs/hero/espresso1.jpg');"></div>
    <div class="carousel-slide" style="background-image: url('public/imgs/hero/espresso2.jpeg');"></div>
    <div class="carousel-slide" style="background-image: url('public/imgs/hero/espresso3.jpg');"></div>
    <div class="carousel-slide" style="background-image: url('public/imgs/hero/espresso4.jpg');"></div>
    <div class="carousel-slide" style="background-image: url('public/imgs/hero/espresso5.jpg');"></div>

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

    {{foreach productosPorCategoria}}
        <!-- Mostrar el nombre de la categoría -->
        <h2 class="categoria-titulo">{{nombre}}</h2>
        
        <!-- Lista de productos en esta categoría -->
        <div class="product-list">
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
                        <!-- Eliminé la línea de {{categoriaId}} ya que ahora se muestra en el título de la categoría -->
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
    {{endfor productosPorCategoria}}
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

  <div class="info-card-full">
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
    let currentTestimonial = 0;

    setInterval(() => {
      testimonials[currentTestimonial].classList.remove("active");
      currentTestimonial = (currentTestimonial + 1) % testimonials.length;
      testimonials[currentTestimonial].classList.add("active");
    }, 4000);

    const slides = document.querySelectorAll("#hero-carousel .carousel-slide");
    let currentSlide = 0;
    
    function nextSlide() {
      slides[currentSlide].classList.remove("active");
      currentSlide = (currentSlide + 1) % slides.length;
      slides[currentSlide].classList.add("active");
    }

    setInterval(nextSlide, 5000);
  });
</script>
