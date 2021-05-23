<?php
  header('Content-type: text/html; charset=utf-8');
  require_once('classes/simple_html_dom.php');
  $html = file_get_html('http://moscowwalking.ru/#schedule');
  
?>
<!doctype html>
<html lang="ru">
  <head>
    <!-- Обязательные метатеги -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title>Moscowwalking парсер</title>
    <style>
      .mini {
        height:250px;
        background-size:cover;
        background-position: center;
      }  
    </style>
  </head>
  <body>
    <div class="container">
      <h1>Учебный парсер сайта moscowwalking.ru</h1>    
      <div class="row">
        <?php foreach($html->find('div.t145__col.t-col.t-col_3') as $div):
            $date = $div->find('div.t145__title.t-title',0)->plaintext;  
            foreach($div->find('strong') as $divmini):
        ?>
        <div class="col-md-6 border my-3" >
            <div class="mini" >
                <?php $link = 'http://moscowwalking.ru'.$divmini->find('a',0)->href;
                    $htmlInner = str_get_html(file_get_html( $link ));
                    $urlInner = $htmlInner->find('div[data-img-zoom-url]',0);
                    $url = explode("'",$urlInner);
                    ?>                  
                <p><?php  echo 'http://moscowwalking.ru'.$url[1]; ?></p>
                <p><?php echo $date?></p>
                <p><?php echo $time = substr($divmini,18,5);?></p>
                
                <p><?php echo $title = $divmini->find('a',0)->plaintext;?></p>
                <p><?php echo $guide = $divmini->find('span',0)->plaintext;?></p>
            </div>
        </div> 
        
        <?php endforeach; ?>
        <?php endforeach; ?>
      </div>
    </div>
    
    
    <!-- Вариант 1: Bootstrap в связке с Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  </body>
</html>