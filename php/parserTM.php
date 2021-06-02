<?php
   
    require_once('parser_start.php');

    global $mysqli;
    global $days;
    global $descrMax;

    $html = file_get_html('https://tvoyamoskva.com/');
    $i=0;
    $site = 'Tvoyamoskva.com';
    $startTime = time();
    $guidesArr = Parse::formGuidesArr();

    foreach($html->find('li.schedule-excursion__item') as $div){
        $dateStr=$div->find('p.schedule-excursion__date',0)->plaintext;
        $day = trim(substr($dateStr,0,2));
        $date = Parse::formDateMonth($dateStr,$day);

        if( strtotime($date) >= (date('U') + ($days*24*60*60)) ) {
            break;
        }

        $strTime = $div->find('p.schedule-excursion__time',0)->plaintext;
        $time = substr($strTime,0,5).':00';

        $title = $div->find('a.schedule-excursion__name',0)->plaintext;
        $link = $div->find('a.schedule-excursion__name',0)->href;
        $guide = $div->find('p.schedule-excursion__guide',0)->plaintext;
        $guideStr = explode(' ',$guide);
        $guide = $guideStr[1].' '.$guideStr[0];
        $guideId = Parse::getGuideId($guide, $guidesArr);

        $htmlInner = file_get_html( $link );
        $img_url = $htmlInner->find('img',0)->getAttribute('src');
        $descr1 = $htmlInner->find('div.about-excursion__description p',3)->plaintext;
        $descr2 = $htmlInner->find('div.about-excursion__description p',4)->plaintext;
        $descr = $descr1.$descr2;
        if(mb_strlen($descr)>$descrMax){
            $descr = Parse::reduceDescr($descr);
        }

        $free = true;

        $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
        VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");   
        $i++;     
    } 
    echo(' Received records from Tvoyamoskva.com: '.$i);
    echo(' Execution time: '.(time() - $startTime).' secs');

?>