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
      <div class="row header my-3 border border-secondary"></div>
        <form class="row filter bg-light" onsubmit="showRecs(this); return false;">
            <div class="filter-site col-3" >
                <p>Сайты для загрузки</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="site1" id="mosstreets" checked>
                    <label class="form-check-label" for="mosstreets">
                      Mosstreets.ru
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="2" name="site2" id="moscowwalking" checked>
                    <label class="form-check-label" for="moscowwalking">
                      Moscowwalking.ru
                    </label>
                </div>
            </div>
            <div class="filter-free col-3" >
              <p>Тип</p>
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
            <div class="filter-date col-3">
              <p>Период</p>
              <select class="form-select" name="date" aria-label="Default select example">
                <option selected value="0">На сегодня</option>
                <option value="1">На 2 дня</option>
                <option value="2">На 3 дня</option>
                <option value="3">На 4 дня</option>
                <option value="4">На 5 дней</option>
                <option value="5">На 6 дней</option>
                <option value="6">На неделю</option> 
              </select>
            </div>
            <div class="filter-guide col-3">
              <p>Экскурсовод</p>
              <select class="form-select" aria-label="Default select example" name="guide" id="guides-list">  
              </select>
            </div>
            <div class="row my-3 row-submit">
              <div class="col-2">
                <button type="submit" class="btn btn-primary">Показать</button>
              </div>  
              <div class="col-2 offset-md-1">Показывать в виде:</div>
              <div class="col-2" >
                <div class="form-check mt-0">
                  <input class="form-check-input" type="radio" name="mode" value="T" id="modeTable">
                  <label class="form-check-label" for="flexRadioDefault1">
                    Таблицы
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="mode" value="C" id="modeCard" checked>
                  <label class="form-check-label" for="flexRadioDefault2">
                    Карточек
                  </label>
                </div>  
              </div>  
              <div class="col-3 offset-md-1">
                <p id="inform"></p>
              </div>     
            </div>
        </form>
        <table class="table table-striped" id="tbody"></table>
      <div id="cards" class="row row-cols-4 gy-4"></div>  
      <div class="row footer my-3 border border-secondary"></div> 
    </div>
    
    <script src="js/script.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    
      
  </body>
</html>
