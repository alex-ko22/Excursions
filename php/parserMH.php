<?php
    require_once('parser_start.php');

    global $mysqli;
    global $days;
    global $descrMax;
    
    $guideId = 0;
    
    $html = file_get_html('https://www.moskvahod.ru/month/%D0%A0%D0%B0%D1%81%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BF%D1%80%D0%BE%D0%B3%D1%83%D0%BB%D0%BE%D0%BA-%D0%BF%D0%BE-%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B5/%D0%BF%D0%B5%D1%88%D0%B5%D1%85%D0%BE%D0%B4%D0%BD%D1%8B%D0%B5-%D1%8D%D0%BA%D1%81%D0%BA%D1%83%D1%80%D1%81%D0%B8%D0%B8/');
    $i=0;
    $site = 'Moskvahod';
    $free = false;

    $startTime = time();
    $guidesArr = Parse::formGuidesArr();

    foreach($html->find('div[data-info]') as $div){
       $dataArr = explode(' ',$div->getAttribute('data-info'));
       $guide = $dataArr[1].' '.$dataArr[0];
       $guideId = Parse::getGuideId($guide, $guidesArr);
       $day = $dataArr[2];
       $date = Parse::formDateMonth(' '.$dataArr[3],$day);
       $time = $dataArr[5].':00';
    
       if( strtotime($date) >= (date('U') + ($days*24*60*60)) ) {
        break;
       }

       $img_url = $div->getAttribute('data-img');
       $link = $div->getAttribute('data-link');
       $title = $div->getAttribute('data-title');

       $htmlInner = file_get_html( $link );
       $descr = ($htmlInner->find('div.catalog-body__about p',0)->plaintext).'...';
       if(mb_strlen($descr)>$descrMax){
         $descr = Parse::reduceDescr($descr);
       }  

        $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
        VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");   
        $i++;      
    } 
    echo(' Received records from Moskvahod: '.$i);
    echo(' Execution time: '.(time() - $startTime).' secs');

?>