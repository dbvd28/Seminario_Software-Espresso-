<link rel="stylesheet" href="{{BASE_DIR}}/public/css/eventos.css" />
<div class="eventos-container">
    <div class="eventos-header">
        <h1>{{page_title}}</h1>
        <p>Descubre nuestras experiencias únicas y eventos especiales</p>
    </div>
    
    <div class="eventos-grid">
        {{foreach eventos}}
            <div class="evento-card">
                <div class="evento-imagen">
                    <img src="{{BASE_DIR}}/{{imagen}}" alt="{{titulo}}">
                    <div class="evento-fecha">{{fecha}}</div>
                </div>
                
                <div class="evento-content">
                    <h3 class="evento-titulo">{{titulo}}</h3>
                    <p class="evento-descripcion">{{descripcionCorta}}</p>
                    
                    <div class="evento-actions">
                        <button class="btn-ver-mas">
                            Ver más detalles
                        </button>
                    </div>
                </div>
            </div>
        {{endfor eventos}}
    </div>
</div>