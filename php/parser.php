<?php
header('Content-type: text/html; charset=utf-8');
require_once('simple_html_dom.php');
require_once('db.php');

$mysqli -> query("TRUNCATE guides");
$mysqli -> query("SET FOREIGN_KEY_CHECKS = 0");
$mysqli -> query("TRUNCATE excursion");

$guidesArr = array();
$guideId = 1;

// Введите количество дней для загрузки в базу
$days = 7;

parseMs($days);
parseMw($days);

// Парсер Mosstreets 

function parseMs($days) {

    global $mysqli;
    global $guidesArr;
    global $guideId;
    
    $html = file_get_html('https://mosstreets.ru/schedule/');
    $i=0;
    $site = 'Mosstreets.ru';

    foreach($html->find('div.trio') as $div){
        $divmini = $div->find('div.mini',0);         
        $url = explode('image:',$divmini = $div->find('div.mini',0)->getAttribute('style'));
        $img_url = mb_substr(mb_substr($url[1],5),0,-2);
        $link = $div->find('p.trio_header a',0)->href;
        
        $title = $div->find('div.desc p',0)->plaintext;
        $htmlInner = file_get_html( $link );
        $descr = $htmlInner->find('div.entry p', 1)->plaintext; 
        $dateStr= $div->find('div.desc p',1)->plaintext;
        $date = '2021-'.substr($dateStr,3,2).'-'.substr($dateStr,0,2);
        $time = substr($dateStr,16,5).':00';
        $price = $div->find('div.desc p',2)->plaintext;

        if( strtotime($date) >= (date('U') + ($days*24*60*60)) ) {
            break;
        }

        if(mb_strlen($descr)>799){
            $descr = reduceDescr($descr);
        }
        
        if( $price == 'бесплатная экскурсия') {
            $free = true;
        } else {$free = false;}    

        $guide = $div->find('span.guide',0)->plaintext;

        // Проверка наличия гида в базе

        $number = array_search($guide,$guidesArr);
        if ($number == false) {
            $guidesArr[$guideId] = $guide;
            $mysqli->query("INSERT INTO `guides` (`id`, `guide`) VALUES ('$guideId', '$guide')");
            $number = $guideId;
            ++$guideId;
        } 

        $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
        VALUES ('$site','$date','$time','$title','$number','$img_url','$free','$link','$descr')");   
        $i++;     
    } 
    echo('Получено записей c Mosstreets.ru: '.$i);
}

// Парсер Moscowwalking

function parseMw($days) {

    global $mysqli;
    global $guidesArr;
    global $guideId;
    
    $html = file_get_html('http://moscowwalking.ru/#schedule');
    $i=0;
    
    foreach($html->find('div.t145__col.t-col.t-col_3') as $div){
        $dateStr = $div->find('div.t145__title.t-title',0)->plaintext;
        
        $day = substr($dateStr,0,2);
        if (strpos($dateStr,'января')) {$date = '2021-01-'.$day;
        }elseif(strpos($dateStr,'февраля')) {$date = '2021-02-'.$day;
        }elseif(strpos($dateStr,'марта')) {$date = '2021-03-'.$day;
        }elseif(strpos($dateStr,'апреля')) {$date = '2021-04-'.$day;
        }elseif(strpos($dateStr,'мая')) {$date = '2021-05-'.$day;
        }elseif(strpos($dateStr,'июня')) {$date = '2021-06-'.$day;
        }elseif(strpos($dateStr,'июля')) {$date = '2021-07-'.$day;
        }elseif(strpos($dateStr,'августа')) {$date = '2021-08-'.$day;        
        }elseif(strpos($dateStr,'сентября')) {$date = '2021-09-'.$day;
        }elseif(strpos($dateStr,'октября')) {$date = '2021-10-'.$day;
        }elseif(strpos($dateStr,'ноября')) {$date = '2021-11-'.$day;
        }elseif(strpos($dateStr,'декабря')) {$date = '2021-12-'.$day;
        }else {$date = '2021-01-01';}   
        
        if( strtotime($date) >= (date('U') + ($days*24*60*60)) ) {
            break;
        }

        $site = 'Moscowwalking.ru';

        foreach($div->find('strong') as $divmini){
            $link = 'http://moscowwalking.ru'.$divmini->find('a',0)->href;
            $htmlInner = str_get_html(file_get_html( $link ));
            $urlInner = $htmlInner->find('div[data-img-zoom-url]',0);
            $url = explode("'",$urlInner);
            $img_url = 'http://moscowwalking.ru'.$url[1];
            $descr = $htmlInner->find('div.t232__text.t-text.t-text_sm',0)->plaintext;

            $time = substr($divmini,18,5).':00';
            $title = $divmini->find('a',0)->plaintext;
            if (strpos($title,'Платная')) {$free = false;
            }else {$free = true;}
            $guide = $divmini->find('span',0)->plaintext;

            if(substr($title,-1,1) == ')'){
                $title = mb_substr($title,0,-9);
            }

            if(mb_strlen($descr)>799){
                $descr = reduceDescr($descr);
            }

            $number = array_search($guide,$guidesArr);
            if ($number == false) {
                $guidesArr[$guideId] = $guide;
                $mysqli->query("INSERT INTO `guides` (`id`, `guide`) VALUES ('$guideId', '$guide')");
                $number = $guideId;
                ++$guideId;
            } 

            $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
                VALUES ('$site','$date','$time','$title','$number','$img_url','$free','$link','$descr')");
            $i++;
        }
    }
    echo('Получено записей c Moscowwalking: '.$i);
}

// Функция обрезания описания 

function reduceDescr($descr) {    
    $arr = explode(' ',mb_substr($descr,0,795));
    array_pop($arr);
    return(implode(' ', $arr).'...');
}

?>