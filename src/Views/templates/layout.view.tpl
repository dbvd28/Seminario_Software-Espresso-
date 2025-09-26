<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{SITE_TITLE}}</title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{BASE_DIR}}/public/css/appstyle.css" />
  <script src="https://kit.fontawesome.com/{{FONT_AWESOME_KIT}}.js" crossorigin="anonymous"></script>
 

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
  <label for="menu_toggle" class="menu_toggle_icon" >
    <div class="hmb dgn pt-1"></div>
    <div class="hmb hrz"></div>
    <div class="hmb dgn pt-2"></div>
  </label>

  <div class="brand" style="display: flex; align-items:center;">
    <img src="public/imgs/hero/logo.png" alt="Coffee Logo" class="logo" style="height:40px; width:auto; border-radius:17px;"/>
    <h1>{{SITE_TITLE}}</h1>
  </div>

  <nav id="menu" style="background-color: #9c653d;">
    <ul>
      <li><a href="index.php?page={{PUBLIC_DEFAULT_CONTROLLER}}">
        <i class="fas fa-home"></i>&nbsp;Inicio</a></li>
      {{foreach PUBLIC_NAVIGATION}}
        <li><a href="{{nav_url}}">{{nav_label}}</a></li>
      {{endfor PUBLIC_NAVIGATION}}
    </ul>
  </nav>
  <span>{{if ~CART_ITEMS}}<a href="index.php?page=Sec-Login"><i class="fa-solid fa-cart-shopping" style="color:white;"></i></a></a>{{~CART_ITEMS}}{{endif ~CART_ITEMS}}</span>
</header>
  <main style="flex:1;">
  {{{page_content}}}
  </main>
  <footer style="background-color: #9c653d;">
    <div>Todo los Derechos Reservados {{~CURRENT_YEAR}} &copy;</div>
  </footer>
  {{foreach EndScripts}}
    <script src="{{~BASE_DIR}}/{{this}}"></script>
  {{endfor EndScripts}}
</body>
</html>
