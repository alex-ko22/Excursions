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

$mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide`, `img_url`, `free`, `link`, `descr`)
                 VALUES ("$rec['site']","$rec['date']","$rec['time']","$rec['title']","$rec['guide']","$rec['img_url']","$rec['free']","$rec['link']","$rec['descr']")");

                 'title','site','img_url','link','date','time','free','guide'


                 <?php

class Parse {
    public static function parseMs() {

        global $mysqli;
        
        $html = file_get_html('https://mosstreets.ru/schedule/');
        $i=0;
        $recs = array();

        foreach($html->find('div.trio') as $div){
            $divmini = $div->find('div.mini',0);
                       
            $recs[$i]['site'] = 'Mossreets.ru';
            $url = explode('image:',$divmini = $div->find('div.mini',0)->getAttribute('style'));
            $recs[$i]['img_url'] = $url[1];
            $link = $div->find('p.trio_header a',0)->href;
            $recs[$i]['link'] = $link;
            $recs[$i]['title'] = $div->find('div.desc p',0)->plaintext;
            /* $htmlInner = file_get_html( $link );
            $recs['descr'] = $htmlInner->find('div.entry p', 1)->plaintext; */
            $dateStr= $div->find('div.desc p',1)->plaintext;
            $recs[$i]['date'] = '2021-'.substr($dateStr,0,2).'-'.substr($dateStr,3,2);
            $recs[$i]['time'] = substr($dateStr,13,2).':'.substr($dateStr,16,2);
            $price = $div->find('div.desc p',2)->plaintext;
            if( $price == 'бесплатная экскурсия') {
                $recs[$i]['free'] = true;
            } else {$recs[$i]['free'] = false;
                }    
            $recs[$i]['guide'] = $div->find('span.guide',0)->plaintext;
            $i+=$i;  
        } 
        var_dump($recs);
        $i=0;
        foreach($recs as $rec){
            $site = $rec[$i]['title'];
            $date = $rec[$i]['date'];
            $time = $rec[$i]['time'];
            $title = $rec[$i]['title'];
            $guide = $rec[$i]['guide'];
            $img_url = $rec[$i]['img_url'];
            $free = $rec[$i]['free'];
            $link = $rec[$i]['link'];
            //$descr = $rec['descr'];
            $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide`, `img_url`, `free`, `link`, `descr`)
            VALUES ('$site','$date','$time','$title','$guide','img_url','$free','$link','$descr')");
            $i+=$i;
        }
    }

    public static function loadToDb($recs) {
        $i = 0;
        foreach($recs as $rec){
            
            $site = $rec['site'];
            $date = $rec['date'];
            $time = $rec['time'];
            $title = $rec['title'];
            $guide = $rec['guide'];
            $img_url = $rec['img_url'];
            $free = $rec['free'];
            $link = $rec['link'];
            $descr = $rec['descr'];

            $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide`, `img_url`, `free`, `link`, `descr`)
                 VALUES ('$site','$date','$time','$title','$guide','img_url','$free','$link','$descr')");
            $i = $i+1;
        }
        return($i);
    }
}

fetch("today").then(response=>response.json())
        .then(result=>{
          result.forEach((recs,index)=>{
          recs += `
            <tr>
              <th scope="row">${index+1}</th>
              <td>${recs.title}</td>
              <td>${recs.date}</td>
              <td>${recs.time}</td>
              
            </tr>
          `
          });
        }
      
      tbody.innerHTML = recs;

?>