<link rel="stylesheet" href="{{BASE_DIR}}/public/css/ubicaciones.css" />
<div class="ubicaciones-container">
    <h1>{{page_title}}</h1>

    <div class="mapa-local-section">
        <h2>Locales en Honduras</h2>
        <p>Encontrá la sucursal más cercana en el mapa. Podés hacer clic en los enlaces para ver la ubicación exacta.
        </p>

        <div class="mapas-container">
            <div class="local-card">
                <p><strong>Lugares Dentro de la Zona:</strong> </p>
                <div class="map-placeholder">
                    <iframe width="100%" height="450" frameborder="0" style="border:1" allowfullscreen loading="lazy"
                        src="https://www.google.com/maps/d/u/0/embed?mid=1DsHVfRCRsxU0oRJjzOjMNv-81HHiWIw&ehbc=2E312F&noprof=1">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="contacto-form-section">
        <h2>Envianos un Mensaje</h2>
        <form action="index.php?page=Ubicaciones" method="POST" class="contact-form">
            <div class="form-group my-3">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre Completo" required
                    class="width-full" />
            </div>
            <div class="form-group my-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email Address" required class="width-full" />
            </div>
            <div class="form-group my-3">
                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto" placeholder="Asunto" required class="width-full" />
            </div>
            <div class="form-group my-3">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" placeholder="Tu Mensaje" rows="5" required
                    class="width-full"></textarea>
            </div>
            <button type="submit" class="btn primary my-3">Enviar Mensaje</button>
        </form>
    </div>
</div>