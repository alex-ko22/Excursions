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
      <div class="row header my-3 position-relative">
        <img src="img/moscow_main.jpg" id="main-image" class="img-fluid px-0" alt="">
        <!-- <a href="#" onclick="showModalWindow('О проекте','about'); return false" class="about position-absolute top-0 start-0">О проекте</a>  -->
        <div class="position-absolute top-50 start-0 text-center">
          <h1 class="d-none d-sm-block" id="text">ВСЕ ЭКСКУРСИИ МОСКВЫ</h1>
          <h2 class="d-sm-none" id="text">ВСЕ ЭКСКУРСИИ МОСКВЫ</h2>
        </div>
      </div>
      <form class="filter bg-light" id="form" onsubmit="showRecs(this); return false;">
          <div class="row row-cols-sm-4">
            <div class="filter-free" >
              <p class="mb-1">Тип</p>
              <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="1" name="free1" id="free" checked>
                  <label class="form-check-label" for="free">
                    Бесплатная 
                  </label>
              </div>
              <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="2" name="free2" id="notfree" checked>
                  <label class="form-check-label" for="notfree">
                    Платная
                  </label>
              </div>
            </div>
            <div class="filter-site" >
              <p class="mb-1">Сайты</p>
              <select class="form-select" name="site" aria-label="Default select example">
                <option selected value=" ">Все 6</option>
                <option id="ms" value="Mosstreets">Mosstreets</option>
                <option value="Moscowwalking">Moscowwalking</option>
                <option value="Tvoyamoskva">Tvoyamoskva</option>
                <option value="Moscoviti">Moscoviti</option>
                <option value="Moskvahod">Moskvahod</option>
                <option value="Moscowsteps">Moscowsteps</option>
              </select>
            </div>
            <div class="filter-date col">
              <p class="mb-1">Дата</p>
              <select class="form-select" aria-label="Default select example" name="date" id="days-list"></select>
            </div>
            <div class="filter-guide">
              <p class="mb-1">Экскурсовод</p>
              <select class="form-select" aria-label="Default select example" name="guide" id="guides-list"></select>
            </div>
          </div>
          <div class="row my-3 row-submit row-cols-2 row-cols-md-4">
                      
            <div class="mt-1 text-end">Показывать в виде:</div>
            <div> 
              <div class="form-check mt-0">
                <input class="form-check-input" type="radio" name="mode" value="C" id="modeCard" onclick="showRecs(form)" checked>
                <label class="form-check-label" for="flexRadioDefault2">
                  Карточек
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="mode" value="T" id="modeTable" onclick="showRecs(form)">
                <label class="form-check-label" for="flexRadioDefault1">
                  Таблицы
                </label>
              </div>  
            </div>
            <div>
              <button type="submit" id="btn" class="btn btn-primary mt-1">Показать</button>
            </div>     
            <div>
              <p id="inform" class="mt-1 text-end"></p>
            </div>     
          </div>
      </form>
      <div class="content">
        <table class="table table-striped" id="tbody"></table>
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
