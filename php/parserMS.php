<?php
    require_once('parser_start.php');

    global $mysqli;
    global $days;
    global $descrMax;
    
    $guideId = 0;
    
    $html = file_get_html('https://mosstreets.ru/schedule/');
    $i=0;
    $site = 'Mosstreets.ru';

    $startTime = time();
    $guidesArr = Parse::formGuidesArr();

    foreach($html->find('div.trio') as $div){
        $divmini = $div->find('div.mini',0);         
               
        $dateStr= $div->find('div.desc p',1)->plaintext;
        $date = '2021-'.substr($dateStr,3,2).'-'.substr($dateStr,0,2);
        $time = substr($dateStr,16,5).':00';
        if( strtotime($date) >= (date('U') + ($days*24*60*60)) ) {
            break;
        }

        $url = explode('image:',$divmini = $div->find('div.mini',0)->getAttribute('style'));
        $img_url = mb_substr(mb_substr($url[1],5),0,-2);
        $title = $div->find('div.desc p',0)->plaintext; 
        $price = $div->find('div.desc p',2)->plaintext;
        if( $price == 'бесплатная экскурсия') {
            $free = true;
        } else {$free = false;} 

        $link = $div->find('p.trio_header a',0)->href;
        $htmlInner = file_get_html( $link );
        $descr = $htmlInner->find('div.entry p', 1)->plaintext; 

        if(mb_strlen($descr)>$descrMax){
            $descr = Parse::reduceDescr($descr);
        }  

        $guide = $div->find('span.guide',0)->plaintext;
        $guideId = Parse::getGuideId($guide, $guidesArr);

        $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
        VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");   
        $i++;     
    } 
    echo(' Received records from Mosstreets.ru: '.$i);
    echo(' Execution time: '.(time() - $startTime).' secs');

?>