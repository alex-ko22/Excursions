<?php
class Parse{
    public static function parseMW() {  //$period = "All" or "Day"

        global $mysqli;
        global $days;
        global $descrMax;
        global $period;
           
        $html = file_get_html('http://moscowwalking.ru/#schedule');
        $i=0;
        $startTime = time();
        $guidesArr = Parse::formGuidesArr();
        
        foreach($html->find('div.t145__col.t-col.t-col_3') as $div){
            $dateStr = $div->find('div.t145__title.t-title',0)->plaintext;
            $day = substr($dateStr,0,2);
            $date = Parse::formDateMonth($dateStr,$day);
            
            if($period == 'Day' && (strtotime($date) !== strtotime('today + '.$days.' day'))){
                continue; 
            }elseif(strtotime($date) > strtotime('today + '.$days.' day')){
                continue;
            }
            
            $site = 'Moscowwalking';
    
            foreach($div->find('strong') as $divmini){
                $link = 'http://moscowwalking.ru'.$divmini->find('a',0)->href;
                $htmlInner = str_get_html(file_get_html( $link ));
                if(!get_headers($link, 1)){
                    continue;
                 }
                $urlInner = $htmlInner->find('div[data-img-zoom-url]',0);
                $url = explode("'",$urlInner);
                $img_url = 'http://moscowwalking.ru'.$url[1];
                $descr = $htmlInner->find('div.t232__text.t-text.t-text_sm',0)->plaintext;
                //$descr = str_replace($descr,'<br>','');
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
        echo(" \nReceived records from Moscowwalking.ru: ".$i);
        echo(" \nExecution time: ".(time() - $startTime)." secs");    

    }

    public static function parseMS() {  //$period = "All" or "Day"

        global $mysqli;
        global $days;
        global $descrMax;
        global $period;

        $guideId = 0;
        
        $html = file_get_html('https://mosstreets.ru/schedule/');
        $i=0;
        $site = 'Mosstreets';
    
        $startTime = time();
        $guidesArr = Parse::formGuidesArr();
    
        foreach($html->find('div.trio') as $div){
            $divmini = $div->find('div.mini',0);         
                   
            $dateStr= $div->find('div.desc p',1)->plaintext;
            $date = '2021-'.substr($dateStr,3,2).'-'.substr($dateStr,0,2);
            $time = substr($dateStr,16,5).':00';

            if($period == 'Day' && (strtotime($date) !== strtotime('today + '.$days.' day'))){
                continue; 
            }elseif(strtotime($date) > strtotime('today + '.$days.' day')){
                continue;
            }

               
            $url = explode('image:',$divmini = $div->find('div.mini',0)->getAttribute('style'));
            $img_url = mb_substr(mb_substr($url[1],5),0,-2);
            $title = $div->find('div.desc p',0)->plaintext; 
            $price = $div->find('div.desc p',2)->plaintext;
            if( $price == 'бесплатная экскурсия') {
                $free = true;
            } else {$free = false;} 
    
            $link = $div->find('p.trio_header a',0)->href;
            if(!get_headers($link, 1)){
                continue;
            }
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
        echo(" \nReceived records from Mosstreets.ru: ".$i);
        echo(" \nExecution time: ".(time() - $startTime)." secs");
    }

    public static function parseTM(){
        
        global $mysqli;
        global $days;
        global $descrMax;
        global $period;
    
        $html = file_get_html('https://tvoyamoskva.com/');
        $i=0;
        $site = 'Tvoyamoskva';
        $startTime = time();
        $guidesArr = Parse::formGuidesArr();
    
        foreach($html->find('li.schedule-excursion__item') as $div){
            $dateStr=$div->find('p.schedule-excursion__date',0)->plaintext;
            $day = trim(substr($dateStr,0,2));
            $date = Parse::formDateMonth($dateStr,$day);
    
            if($period == 'Day' && (strtotime($date) !== strtotime('today + '.$days.' day'))){
                continue; 
            }elseif(strtotime($date) > strtotime('today + '.$days.' day')){
                continue;
            }
    
            $strTime = $div->find('p.schedule-excursion__time',0)->plaintext;
            $time = substr($strTime,0,5).':00';
    
            $title = $div->find('a.schedule-excursion__name',0)->plaintext;
            $link = $div->find('a.schedule-excursion__name',0)->href;
            if(!get_headers($link, 1)){
                continue;
            }
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
        echo(" \nReceived records from Tvoyamoskva.com: ".$i);
        echo(" \nExecution time: ".(time() - $startTime)." secs");

    }

    public static function parseMV(){

        global $mysqli;
        global $days;
        global $descrMax;
        global $period;

        $html = file_get_html('https://moscoviti.ru/raspisanie/');
        $i=0;
        $site = 'Moscoviti';
        $startTime = time();
        $guidesArr = Parse::formGuidesArr();

        foreach($html->find('span.tg-block') as $div){
            
            $link = $div->find('a',0)->href;
            if(mb_substr($link,0,3) != 'htt') {
                $link = 'https://moscoviti.ru/product/'.$link;
            }
            if(!get_headers($link, 1)){
                continue;
            }

            $title = $div->find('a',0)->plaintext;
            $htmlInner = file_get_html( $link );
            $url = explode('url(',($htmlInner->find('div.elementor-cta__bg.elementor-bg',0)->getAttribute('style')));
            $img_url = mb_substr($url[1],0,-2);

            $dateStr = $htmlInner->find('tbody p',0)->innertext;
            $day = trim(mb_substr((explode(',',$dateStr)[1]),0,3));
            $date = Parse::formDateMonth($dateStr,$day);
            $time = explode('в ',$dateStr)[1].':00';
            if($period == 'Day' && (strtotime($date) !== strtotime('today + '.$days.' day'))){
                continue; 
            }elseif(strtotime($date) > strtotime('today + '.$days.' day')){
                continue;
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
        echo("\n\rReceived records from Moscoviti.ru: ".$i);
        echo(" \nExecution time: ".(time() - $startTime)." secs");
    }

    public static function parseMH() {

        global $mysqli;
        global $days;
        global $descrMax;
        global $period;
        
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
        
           if($period == 'Day' && (strtotime($date) !== strtotime('today + '.$days.' day'))){
            continue; 
            }elseif(strtotime($date) > strtotime('today + '.$days.' day')){
                continue;
            }
        
           $img_url = $div->getAttribute('data-img');
           $link = $div->getAttribute('data-link');
           if(!get_headers($link, 1)){
            continue;
            }
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
    }

    public static function formDateMonth($dateStr,$day) {
        if (strlen($day) == 1){
            $day = '0'.$day;
        }
        if (strpos($dateStr,'января') != false) {$date = '2021-01-'.$day;
            }elseif(strpos($dateStr,'феврал') != false) {$date = '2021-02-'.$day;
            }elseif(strpos($dateStr,'март') != false) {$date = '2021-03-'.$day;
            }elseif(strpos($dateStr,'апрел') != false) {$date = '2021-04-'.$day;
            }elseif(strpos($dateStr,'мая') != false || strpos($dateStr,'май') != false) {$date = '2021-05-'.$day;
            }elseif(strpos($dateStr,'июн') != false) {$date = '2021-06-'.$day;
            }elseif(strpos($dateStr,'июл') != false) {$date = '2021-07-'.$day;
            }elseif(strpos($dateStr,'август') != false) {$date = '2021-08-'.$day;        
            }elseif(strpos($dateStr,'сентябр') != false) {$date = '2021-09-'.$day;
            }elseif(strpos($dateStr,'октябр') != false) {$date = '2021-10-'.$day;
            }elseif(strpos($dateStr,'ноябр') != false) {$date = '2021-11-'.$day;
            }elseif(strpos($dateStr,'декабр') != false) {$date = '2021-12-'.$day;
            }else {$date = '2021-01-01';}
            
        return($date);
    }

    public static function formGuidesArr() {
        global $mysqli;
        
        $result = mysqli_query($mysqli,"SELECT * FROM `guides`");

        $recs = [];
        while( $row = $result->fetch_assoc() ){
            $recs[] = $row;
        }
        return($recs);
    }

    public static function getGuideId($guide) {
        global $mysqli;
        
        $found = false;
        $maxId = 0;

        $result = mysqli_query($mysqli,"SELECT * FROM `guides`");

        $recs = [];
        while( $row = $result->fetch_assoc() ){
            $recs[] = $row;
        }

        foreach( $recs as $arr){
            if($guide == $arr['guide']){
                $guideId = $arr['id'];
                $found = true;
            }
            $maxId = $arr['id'];
        }

        if (!$found) {
            $guideId = $maxId + 1;
            $mysqli -> query("SET FOREIGN_KEY_CHECKS = 0");
            $mysqli->query("INSERT INTO `guides` (`id`,`guide`) VALUES ('$guideId','$guide')");
            echo('Added guide: '.$guide.'(id='.$guideId.') ');
        }

        return($guideId);
    }

    public static function reduceDescr($descr) {    
        $arr = explode(' ',mb_substr($descr,0,795));
        array_pop($arr);
        return(implode(' ', $arr).'...');
    }

}   // End of class


?>