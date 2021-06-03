<?php
    require_once('parser_start.php');
    
    global $mysqli;
    global $days;
       
    $html = file_get_html('http://moscowwalking.ru/#schedule');
    $i=0;
    $startTime = time();
    $guidesArr = Parse::formGuidesArr();
    
    foreach($html->find('div.t145__col.t-col.t-col_3') as $div){
        $dateStr = $div->find('div.t145__title.t-title',0)->plaintext;
        $day = substr($dateStr,0,2);
        $date = Parse::formDateMonth($dateStr,$day);
               
        if( strtotime($date) >= (date('U') + ($days*24*60*60)) ) {
            break;
        }

        $site = 'Moscowwalking';

        foreach($div->find('strong') as $divmini){
            $link = 'http://moscowwalking.ru'.$divmini->find('a',0)->href;
            $htmlInner = str_get_html(file_get_html( $link ));
            $urlInner = $htmlInner->find('div[data-img-zoom-url]',0);
            $url = explode("'",$urlInner);
            $img_url = 'http://moscowwalking.ru'.$url[1];
            $descr = $htmlInner->find('div.t232__text.t-text.t-text_sm',0)->plaintext;
            if(mb_strlen($descr)>$descrMax){
                $descr = Parse::reduceDescr($descr);
            }

            $time = substr($divmini,18,5).':00';
            $title = $divmini->find('a',0)->plaintext;
            if (strpos($title,'Платная')) {$free = false;
            }else {$free = true;}
            if(substr($title,-1,1) == ')'){
                $title = mb_substr($title,0,-9);
            }

            $guide = $divmini->find('span',0)->plaintext;
            $guideStr = explode(' ',$guide);
            $guide = $guideStr[1].' '.$guideStr[0];
            $guideId = Parse::getGuideId($guide, $guidesArr);

            $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
                VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");
            $i++;
        }
    }
    echo(' Received records from Moscowwalking.ru: '.$i);
    echo(' Execution time: '.(time() - $startTime).' secs');


?>