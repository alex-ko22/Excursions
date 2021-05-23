<?php
  header('Content-type: text/html; charset=utf-8');
  require_once('classes/simple_html_dom.php');
  $html = file_get_html('https://mosstreets.ru/schedule/');
  
?>
<!doctype html>
<html lang="ru">
  <head>
    <!-- Обязательные метатеги -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title>Mosstreets парсер</title>
    <style>
      .mini {
        height:250px;
        background-size:cover;
        background-position: center;
      }
      .tip {
        position:absolute;
        display:none;
        width:250px;
        height:350px;
        margin-left:20px;
        margin-top: 5px;
        z-index:25;
      }
      #title:hover .tip {
        color:red;
        display:block;
        
      }
      
    </style>
  </head>
  <body>
    <div class="container">
      <h1>Учебный парсер сайта mosstreets.ru</h1>    
      <div class="row">
        <?php foreach($html->find('div.trio') as $div):?>
    
        <?php $divmini = $div->find('div.mini',0);?>
        <div class="col-md-6 border my-3" >
         <div class="mini" style="background-image:
             <?php $url = explode('image:',$divmini = $div->find('div.mini',0)->getAttribute('style1'));
                echo $url[1];?>"> 
         </div> 
         <p><?php echo $link = $div->find('p.trio_header a',0)->href?></p>
         <p id="title"><?php echo $title = $div->find('div.desc p',0)->plaintext?></p>
         <div class="tip">
          <?php
            $htmlInner = file_get_html( $link );
            echo $despription = $htmlInner->find('div.entry p', 1)->plaintext;
          ?>
        </div>
         <p><?php echo $date = $div->find('div.desc p',1)->plaintext;?></p>
         <p><?php echo $cost = $div->find('div.desc p',2)->plaintext;?></p>
         <p><?php echo $guide = $div->find('span.guide',0)->plaintext;?></p>
        </div> 
        
        
        <?php endforeach; ?>
      </div>
    </div>
    
    
    <!-- Вариант 1: Bootstrap в связке с Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  </body>
</html>