<!doctype html>
<html lang="ru">
  <head>
    <!-- Обязательные метатеги -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="origin"/>
    <meta name="description" content="На сайте в единообразном виде представлены пешие (и не только) экскурсии по Москве на ближайшие дни от наиболее популярных тематических площадок"/>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
      (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
      m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
      (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

      ym(81239251, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
      });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/81239251" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="https://mos-guides.ru/favicon.png" type="image/x-icon">
    <title>Все экскурсии Москвы</title>
  </head>
  <body>
    <div class="container">
      <div class="row header my-2 position-relative">
        <img src="img/moscow_main.jpg" id="main-image" class="img-fluid px-0" alt="">
        <div class="position-absolute top-50 start-0 text-center">
          <h1 class="d-none d-sm-block" id="text">ВСЕ ЭКСКУРСИИ МОСКВЫ</h1>
          <h2 class="d-sm-none" id="text">ВСЕ ЭКСКУРСИИ МОСКВЫ</h2>
        </div>
      </div>

      <form id="form" class="row my-2 info" onsubmit="showRecs(this); return false;">
        <ul id="list">
          <li class="info-line">Тип: <select name="type">
              <option selected value="All">Все</option>
              <option value="Free">Бесплатные</option>
              <option value="Notfree">Платные</option>
            </select></li>
          <li class="info-line">Сайт: <select name="site">
              <option selected value="All">Все</option>
              <option value="Mosstreets">Mosstreets</option>
              <option value="Moscowwalking">Moscowwalking</option>
              <option value="Tvoyamoskva">Tvoyamoskva</option>
              <option value="Moscoviti">Moscoviti</option>
              <option value="Moskvahod">Moskvahod</option>
              <option value="Moscowsteps">Moscowsteps</option>
            </select></li>
          <li class="info-line">Дата: <select name="date" id="days-list"></select></li>
          <li class="info-line">Экскурсовод: <select name="guide" id="guides-list"></select></li>
          <li class="info-line"><button type="submit" id="btn">Применить</button></li>
          <li class="info-line" id="info-count">Найдено экскурсий:  <span id="inform"></span></li>
        </ul>
      </form>

      <div class="content">
        <div id="cards" class="row row-cols-2 row-cols-sm-3 row-cols-lg-5 row-cols-xl-6 gy-2 justify-content-center"></div>
      </div> 
      <hr>
      <!-- Подложка под модальным окном -->
      <div id="overlay"></div>
      <!-- Модальное окно -->
      <div id="modal" >
        <img id="modal_cross" src="img/x-square.svg" alt="">
        <h5 id="modal_guide"></h5>
        <img id="modal_img" src="" alt=""  class="img-fluid">
        <p id="modal_content"></p>
      </div>
    </div>
    <div class="footer">
        <p id="copiright">© 2021 Copyright: AEK</p>
        <img src="img/envelope.svg" alt="">
        <p id="email">mos-guides@mail.ru</p>
        <p id="share" class="ya-share2" data-services="vkontakte,twitter,facebook,viber,whatsapp,telegram"></p>
    </div>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://yastatic.net/share2/share.js" async></script>
  </body>
</html>
