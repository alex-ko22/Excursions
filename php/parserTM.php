<?php
    header('Content-type: text/html; charset=utf-8');
    require_once('simple_html_dom.php');
    require_once('db.php');
    require_once('classes/Parse.php');

    function parseMs($days) {

    global $mysqli;
    global $guidesArr;
    global $guideId;

    $html = file_get_html('https://tvoyamoskva.com/');
    $i=0;
    $site = 'Tvoyamoskva.com';

    foreach($html->find('li.schedule-excursion__item') as $div){
        $dateStr=$div->find('p.schedule-excursion__date',0)->plaintext;
        $day = trim(substr($dateStr,0,2));
        $date = Parse::formDateMonth($dateStr,$day);

        if( strtotime($date) >= (date('U') + ($days*24*60*60)) ) {
            break;
        }

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

?>