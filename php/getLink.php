<?php
    require 'simple_html_dom.php'; //подключаем библиотеку
    $link = 'https://www.youtube.com/watch?v=kdmBTTAFlk0';
    $html = file_get_html( $link ); // получаем страницу

    $load = file_get_contents( $link );
    $html= str_get_html( $load );

    echo $element = $html->find('#collapsible', 0);
?>


style1="background-image:
                <?php 
                    $link = 'http://moscowwalking.ru'.$divmini->find('a',0)->href;
                    $htmlInner = file_get_html( $link );
                    $url = explode("'",$urlInner = $htmlInner->find('div.t-carousel__item__img.t-zoomable',0)->getAttribute('style'));
                    echo('http://moscowwalking.ru'.$url[1]);
                    
                ?>"> 

<P><?php 
                    $htmlInner = file_get_html($link);
                    $urlInner = $htmlInner->find('div[data-img-zoom-url]',0)->outertext;
                    echo 'http://moscowwalking.ru'.$urlInner; ?></p>