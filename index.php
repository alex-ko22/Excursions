<!doctype html>
<html lang="ru">
  <head>
    <!-- Обязательные метатеги -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Экскурсии в Москве</title>
  </head>
  <body>
    <div class="container">
      <div class="row header my-3 position-relative">
        <img src="img/DSC01860.jpg" id="main-image" class="img-fluid px-0" alt="">
        <a href="#" data-bs-toggle="modal" data-bs-target="#mod" class="about position-absolute top-0 start-0">О проекте</a>
        <div class="position-absolute top-50 start-0 text-center">
         <h1 id="text">ВСЕ ЭКСКУРСИИ МОСКВЫ</h1>
        </div>
      </div>
      <form class="filter bg-light" id="forma" onsubmit="showRecs(this); return false;">
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
                <option selected value=" ">Все 5</option>
                <option id="ms" value="Mosstreets">Mosstreets</option>
                <option value="Moscowwalking">Moscowwalking</option>
                <option value="Tvoyamoskva">Tvoyamoskva</option>
                <option value="Moscoviti">Moscoviti</option>
                <option value="Moskvahod">Moskvahod</option>
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
                <input class="form-check-input" type="radio" name="mode" value="C" id="modeCard" onclick="showRecs(forma)" checked>
                <label class="form-check-label" for="flexRadioDefault2">
                  Карточек
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="mode" value="T" id="modeTable" onclick="showRecs(forma)">
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
        <div id="cards" class="row row-cols-2 row-cols-sm-3 row-cols-lg-5 gy-2 justify-content-center"></div> 
      </div> 
      <hr>
         
      <div class="modal" tabindex="-1" id="mod">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">О проекте</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="text-align:justify">
              <p>Этот сайт возник как учебный проект для сдачи диплома на 2х месячных курсах по web-программированию. 
                При выборе темы диплома мне показалось интересным собрать в одном месте все предложения по пешим 
                экскурсиям по Москве с соответствующих сайтов. Сам я периодически посещаю подобные мероприятия, мне 
                нравится прогуляться по Москве пару часов, да при этом ещё и узнать что-то новенькое - приятно и полезно. 
                Но каждый раз приходилось заглядывать на разные ресурсы, чтобы найти подходящий вариант. Теперь стало 
                попроще). Страничка получилась немудрёной, но задачи свои выполняет. Если у публики будет интерес к моей идее,
                то проект можно будет развить, добавляя функционал и контент. Все ваши отзывы, пожелания, негодования отправляйте
                на адрес электронной почты, указанный внизу основной страницы. 
              </p>
              <p style="text-align:right">Александр</p>
            </div>
           
          </div>
        </div>
      </div>

    </div>
    <div class="footer">
        <p id="copiright">© 2021 Copyright: AEK</p>
        <img src="img/envelope.svg" alt="">
        <p id="email">mos-guides@mail.ru</p>
    </div>
    
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
      
  </body>
</html>
