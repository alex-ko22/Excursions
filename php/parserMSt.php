<?php
    require_once('parser_start.php');
    
    global $mysqli;
       
    $html = file_get_html('https://moscowsteps.com/timetable');
    $i=0;
    $startTime = time();
    $guidesArr = Parse::formGuidesArr();
    $foundDay = false;
    $free = false;
    $site = 'Moscowsteps';

    foreach($html->find('table.calendar') as $tableMonth){
        $monthStr = $tableMonth->find('td.month strong')[0]->innertext;
        foreach($tableMonth->find('td[id]') as $tableDay){
            $date= $tableDay->getAttribute('data-fulldate');

            if (Parse::checkDate($date)){
                continue;
            }else{
                $foundDay = true;
            }
            foreach($tableDay->find('span.dop') as $div){
                $time = $div->find('b.time_ex',0)->plaintext;
                $title = $div->find('a',0)->plaintext;
                $link = $div->find('a',0)->href;
                $link = 'https://moscowsteps.com/'.$link;
                $htmlInner = file_get_html( $link );
                $img_url = $htmlInner->find('td.mmm img',0)->getAttribute('src');
                $img_url = 'https://moscowsteps.com/'.$img_url;
                $guideStr = explode(' ',$htmlInner->find('a[href=/guides]',0)->innertext);
                $guide = $guideStr[1].' '.$guideStr[0];
                $guideId = Parse::getGuideId($guide, $guidesArr);
                $descr = $htmlInner->find('div[style=text-align: justify!important;] span',0)->plaintext;
                if (strtok($descr,'!') == 'ВНИМАНИЕ'){
                    $descr = $htmlInner->find('div[style=text-align: justify!important;] span',1)->plaintext;
                }
                $descr = Parse::reduceDescr($descr);

                $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
                VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");
                $i++;
            }
            if($foundDay){
                continue;
            }          
        }
        if($foundDay){
            continue;
        }   
    }
    echo(' Received records from Moscowsteps.com: '.$i);
    echo(' Execution time: '.(time() - $startTime).' secs');   

?>