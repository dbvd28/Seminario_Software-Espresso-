<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{SITE_TITLE}}</title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{BASE_DIR}}/public/css/appstyle-copy.css" />
  <script src="https://kit.fontawesome.com/{{FONT_AWESOME_KIT}}.js" crossorigin="anonymous"></script>
  <style>
    /* Submenús colapsables en navegación privada */
    nav#menu ul li.submenu > ul.submenu-items { display: none !important; }
    nav#menu ul li.submenu.open > ul.submenu-items { display: block !important; }
    nav#menu ul li.submenu > ul.submenu-items { flex-direction: column; padding-left: 0; }
    nav#menu ul li.submenu > .submenu-toggle { cursor: pointer; display: inline-flex; align-items: center; gap: 6px; pointer-events: auto; justify-content: flex-start; width: 100%; position: relative; }
    nav#menu ul li.submenu > .submenu-toggle .chevron { 
        transition: transform 0.2s ease, opacity 0.2s ease; 
        position: absolute;
        right: 8px;
        opacity: 0.85;
        display: inline-block !important;
    }
    /* Flechita CSS pura */
    nav#menu ul li.submenu > .submenu-toggle .chevron:before {
        content: "▼";
        font-size: 12px;
        display: inline-block;
        color: inherit;
    }
    nav#menu ul li.submenu.open > .submenu-toggle .chevron { transform: rotate(180deg); opacity: 1; }
  </style>
  {{foreach SiteLinks}}
  <link rel="stylesheet" href="{{~BASE_DIR}}/{{this}}" />
  {{endfor SiteLinks}}
  {{foreach BeginScripts}}
  <script src="{{~BASE_DIR}}/{{this}}"></script>
  {{endfor BeginScripts}}
</head>

<body>
  <header style="background-color: #9c653d;">
    <input type="checkbox" class="menu_toggle" id="menu_toggle" />
    <label for="menu_toggle" class="menu_toggle_icon">
      <div class="hmb dgn pt-1"></div>
      <div class="hmb hrz"></div>
      <div class="hmb dgn pt-2"></div>
    </label>
    <div class="brand" style="display: flex; align-items:center; gap:12px;">
      <img src="public/imgs/hero/logo.png" alt="Coffee Logo" class="logo" style="height:40px; width:auto;" />
      <h1>{{SITE_TITLE}}</h1>
    </div>
    <nav id="menu" style="background-color: #9c653d;">
      <ul>
        <li><a href="index.php?page={{PRIVATE_DEFAULT_CONTROLLER}}"><i class="fas fa-home"></i>&nbsp;Inicio</a></li>

        {{if ~IS_ADMIN_MODE}}
        <li class="submenu" id="submenu-admin">
          <span class="submenu-toggle" role="button" tabindex="0" aria-controls="submenu-admin-items" onclick="this.parentElement.classList.toggle('open'); this.setAttribute('aria-expanded', this.parentElement.classList.contains('open') ? 'true' : 'false');"><i class="fas fa-cogs"></i>&nbsp;Administrar <span class="chevron"></span></span>
          <ul class="submenu-items" id="submenu-admin-items">
            {{foreach NAV_ADMIN}}
            <li><a href="{{nav_url}}">{{nav_label}}</a></li>
            {{endfor NAV_ADMIN}}
          </ul>
        </li>
        {{endif ~IS_ADMIN_MODE}}

        {{if ~IS_USER_MODE}}
          {{foreach NAV_USER}}
          <li><a href="{{nav_url}}">{{nav_label}}</a></li>
          {{endfor NAV_USER}}
        {{endif ~IS_USER_MODE}}

        <li class="submenu" id="submenu-edit">
          <span class="submenu-toggle" role="button" tabindex="0" aria-controls="submenu-edit-items" onclick="this.parentElement.classList.toggle('open'); this.setAttribute('aria-expanded', this.parentElement.classList.contains('open') ? 'true' : 'false');"><i class="fas fa-user-cog"></i>&nbsp;Editar perfil <span class="chevron"></span></span>
          <ul class="submenu-items" id="submenu-edit-items">
            {{foreach NAV_EDIT}}
            <li><a href="{{nav_url}}">{{nav_label}}</a></li>
            {{endfor NAV_EDIT}}
          </ul>
        </li>

        <li><a href="index.php?page=sec_logout"><i class="fas fa-sign-out-alt"></i>&nbsp;Salir</a></li>
      </ul>
    </nav>
    <span>{{if ~CART_ITEMS}}<a href="index.php?page=Checkout-Checkout"><i class="fa-solid fa-cart-shopping"
          style="color:white;"></i></a></a>{{~CART_ITEMS}}{{endif ~CART_ITEMS}}</span>
    {{with login}}
    <span class="username">{{userName}} <a href="index.php?page=sec_logout"><i
          class="fas fa-sign-out-alt"></i></a></span>
    {{endwith login}}
  </header>
  <main style="flex:1;">
    {{{page_content}}}
  </main>
  <footer style="background-color: #9c653d;">
   <div class="footer-columna-derechos">
        <div>Todo los Derechos Reservados {{~CURRENT_YEAR}} &copy;</div>
    </div>

    <div class="footer-columna-informacion">
        <h4>Informacion</h4>
        <a href="index.php?page=Ubicaciones">Encontrá tu Local Más Cercano</a>
        <br>
        <a href="index.php?page=Eventos">Proximos Eventos</a>
    </div>
  </footer>
  {{foreach EndScripts}}
  <script src="{{~BASE_DIR}}/{{this}}"></script>
  {{endfor EndScripts}}
  <script>
    // Toggle de submenús colapsables con delegación de eventos (más robusto)
    (function(){
      var nav = document.querySelector('nav#menu');
      if (!nav) return;
      function toggle(li){
        if (!li) return;
        li.classList.toggle('open');
        var isOpen = li.classList.contains('open');
        var btn = li.querySelector('.submenu-toggle');
        if (btn) { btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false'); }
      }
      nav.addEventListener('click', function(e){
        var btn = e.target.closest('.submenu-toggle');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();
        toggle(btn.parentElement);
      });
      nav.addEventListener('keydown', function(e){
        var btn = e.target.closest('.submenu-toggle');
        if (!btn) return;
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          toggle(btn.parentElement);
        }
      });
    })();
  </script>
</body>

</html>