<?php
   
    require_once('parser_start.php');

    global $mysqli;
    global $days;
    global $descrMax;

    $html = file_get_html('https://moscoviti.ru/raspisanie/');
    $i=0;
    $site = 'Moscoviti.ru';
    $startTime = time();
    $guidesArr = Parse::formGuidesArr();

    foreach($html->find('span.tg-block') as $div){
        
        $link = $div->find('a',0)->href;
        if(mb_substr($link,0,3) != 'htt') {
            $link = 'https://moscoviti.ru/product/'.$link;
        }

        $title = $div->find('a',0)->plaintext;
        $htmlInner = file_get_html( $link );
        $url = explode('url(',($htmlInner->find('div.elementor-cta__bg.elementor-bg',0)->getAttribute('style')));
        $img_url = mb_substr($url[1],0,-2);

        $dateStr = $htmlInner->find('tbody p',0)->innertext;
        $day = trim(mb_substr((explode(',',$dateStr)[1]),0,3));
        $date = Parse::formDateMonth($dateStr,$day);
        $time = mb_substr($dateStr,-5).':00';
        if( strtotime($date) >= (date('U') + ($days*24*60*60)) ) {
            break;
        }

        if(($htmlInner->find('tbody p',3)->innertext) == 'бесплатная'){
            $free = true;
        }else {$free = false;}

        $guide = $htmlInner->find('tbody p',4)->innertext;
        $guideStr = explode(' ',$guide);
        $guide = $guideStr[1].' '.$guideStr[0];
        $guideId = Parse::getGuideId($guide, $guidesArr);
        
        $descr = $htmlInner->find('div[role=tabpanel] p',0)->innertext;
        if(mb_strlen($descr)>$descrMax){
            $descr = Parse::reduceDescr($descr);
        }

        $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
        VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");   
        $i++;  
    } 
    echo(' Received records from Moscoviti.ru: '.$i);
    echo(' Execution time: '.(time() - $startTime).' secs');

?>