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
                <img src="{{imagen}}" alt="{{titulo}}">
                <div class="evento-fecha">{{fecha}}</div>
            </div>

            <div class="evento-content">
                <h3 class="evento-titulo">{{titulo}}</h3>
                <p class="evento-descripcion">{{descripcionCorta}}</p>
                <div class="evento-descripcion-larga" style="display:none;">{{descripcionLarga}}</div>
                <div class="evento-actions">
                    <button class="btn-ver-mas">
                        Ver más detalles
                    </button>
                </div>
            </div>
        </div>
        {{endfor eventos}}
    </div>
    <!-- Modal para ver más detalles -->
    <div class="evento-modal" id="eventoModal" aria-hidden="true">
        <div class="modal-content" role="dialog" aria-modal="true">
            <div class="close-modal" id="closeModal">&times;</div>
            <div class="modal-imagen">
                <img id="modalImagen" src="" alt="">
            </div>
            <div class="modal-info">
                <h2 id="modalTitulo"></h2>
                <div class="modal-fecha" id="modalFecha"></div>
                <p class="modal-descripcion" id="modalDescripcion"></p>
                <button class="btn-cerrar" id="btnCerrar" type="button">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("eventoModal");
    const modalImagen = document.getElementById("modalImagen");
    const modalTitulo = document.getElementById("modalTitulo");
    const modalDescripcion = document.getElementById("modalDescripcion");
    const modalFecha = document.getElementById("modalFecha");
    const closeModal = document.getElementById("closeModal");
    const btnCerrar = document.getElementById("btnCerrar");

    document.querySelectorAll(".btn-ver-mas").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();

            const card = btn.closest(".evento-card");
            if (!card) return;

            const imgEl = card.querySelector(".evento-imagen img");
            const titulo = card.querySelector(".evento-titulo")?.innerText || "";
            const fecha = card.querySelector(".evento-fecha")?.innerText || "";
            const descripcionLargaEl = card.querySelector(".evento-descripcion-larga");
            const descripcionLarga = descripcionLargaEl ? descripcionLargaEl.innerText.trim() : "";

            if (imgEl && imgEl.src) {
                modalImagen.src = imgEl.src;
                modalImagen.alt = titulo;
            } else {
                modalImagen.src = "";
                modalImagen.alt = "";
            }

            modalTitulo.textContent = titulo;
            modalFecha.textContent = fecha;
            modalDescripcion.textContent = descripcionLarga;
            modal.style.display = "flex";
            modal.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
        });
    });

    const cerrar = () => {
        modal.style.display = "none";
        modal.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
        modalImagen.src = "";
    };

    closeModal.addEventListener("click", cerrar);
    btnCerrar.addEventListener("click", cerrar);

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && modal.style.display === "flex") {
            cerrar();
        }
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) cerrar();
    });
});
</script>
