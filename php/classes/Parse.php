<?php

/**
 * Класс, отвечающий за парсинг
 */
class Parse{
    /**
     * Парсинг сайта moscowwalking.ru
     */
    public static function parseMW() {
        global $mysqli;
        global $fOpen;
           
        $html = file_get_html('https://moscowwalking.ru/#schedule');
        $i = 0;
        $startTime = time();
        
        foreach($html->find('div.t145__col.t-col.t-col_3') as $div){
            $dateStr = $div->find('div.t145__title.t-title',0)->plaintext;
            $day = substr($dateStr,0,2);
            $date = Parse::formDateMonth($dateStr,$day);
            $option = Parse::checkDate($date);
            if ($option == 1){
                continue;
            }elseif($option == 2){
                break;
            }

            $site = 'Moscowwalking';

            foreach($div->find('strong') as $divmini){
                $link = 'https://moscowwalking.ru'.$divmini->find('a',0)->href;
                $htmlInner = str_get_html(file_get_html( $link ));
                if(!get_headers($link, 1)){
                    continue;
                 }
                $urlInner = $htmlInner->find('div[data-img-zoom-url]',0);
                $url = explode("'",$urlInner);
                $img_url = 'https://moscowwalking.ru'.$url[1];

                $url_tmp = Parse::saveImgFile($img_url);
                if ($url_tmp != '0'){
                    $img_url = $url_tmp;
                }

                $descr = $htmlInner->find('div.t232__text.t-text.t-text_sm',0)->plaintext;
                $descr = Parse::reduceDescr($descr);

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
                $guideId = Parse::getGuideId($guide);

                $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
                    VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");
                $i++;
            }
        }
        fwrite($fOpen,'Received records from Moscowwalking.ru: '.$i);
        fwrite($fOpen,'  Execution time: '.(time() - $startTime).' secs'."\n");
    }

    /**
     * Парсинг сайта mosstreets.ru
     */
    public static function parseMS() {
        global $mysqli;
        global $fOpen;

        $html = file_get_html('https://mosstreets.ru/schedule/');
        $i = 0;
        $site = 'Mosstreets';
        $startTime = time();

        foreach($html->find('div.trio') as $div){
            $dateStr= $div->find('div.desc p',1)->plaintext;
            $year = getdate(strtotime('today'))['year'];
            $date = $year.'-'.substr($dateStr,3,2).'-'.substr($dateStr,0,2);
            $time = substr($dateStr,16,5).':00';

            $option = Parse::checkDate($date);
            if ($option == 1){
                continue;
            }elseif($option == 2){
                break;
            }

            $url = explode('image:',$div->find('div.mini',0)->getAttribute('style'));
            $img_url = mb_substr(mb_substr($url[1],5),0,-2);
            $url_tmp = Parse::saveImgFile($img_url);
            if ($url_tmp != '0'){
                $img_url = $url_tmp;
            }
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
            $descr = Parse::reduceDescr($descr);

            $guide = $div->find('span.guide',0)->plaintext;
            $guideId = Parse::getGuideId($guide);

            $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
            VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");
            $i++;
        }
        fwrite($fOpen,'Received records from Mosstreets.ru: '.$i);
        fwrite($fOpen,'  Execution time: '.(time() - $startTime).' secs'."\n");
    }

    /**
     * Парсинг сайта tvoyamoskva.ru
     */
    public static function parseTM(){
        global $mysqli;
        global $fOpen;

        $html = file_get_html('https://tvoyamoskva.com/');
        $i = 0;
        $site = 'Tvoyamoskva';
        $startTime = time();

        foreach($html->find('li.schedule-excursion__item') as $div){
            $dateStr=$div->find('p.schedule-excursion__date',0)->plaintext;
            $day = trim(substr($dateStr,0,2));
            $date = Parse::formDateMonth($dateStr,$day);
            $option = Parse::checkDate($date);
            if ($option == 1){
                continue;
            }elseif($option == 2){
                break;
            }

            foreach($div->find('li.schedule-excursion__info-item') as $divInner ) {
                $strTime = $divInner->find('p.schedule-excursion__time', 0)->plaintext;
                $time = substr($strTime, 0, 5) . ':00';

                $title = $divInner->find('a.schedule-excursion__name', 0)->plaintext;
                $link = $divInner->find('a.schedule-excursion__name', 0)->href;
                if (!get_headers($link, 1)) {
                    continue;
                }
                $guide = $divInner->find('p.schedule-excursion__guide', 0)->plaintext;
                $guideStr = explode(' ', $guide);
                $guide = $guideStr[1] . ' ' . $guideStr[0];
                $guideId = Parse::getGuideId($guide);

                $htmlInner = file_get_html($link);
                $img_url = $htmlInner->find('img', 0)->getAttribute('src');
                if ($img_url == '') {
                    $img_url = $htmlInner->find('img', 1)->getAttribute('src');
                }

                if(strpos($img_url,'informer.yandex') !== false) $img_url = '../img/exc_imgs/sample.jpeg';

                $url_tmp = Parse::saveImgFile($img_url);
                if ($url_tmp != '0') {
                    $img_url = $url_tmp;
                }
                $descr1 = $htmlInner->find('div.about-excursion__description p', 3)->plaintext;
                $descr2 = $htmlInner->find('div.about-excursion__description p', 4)->plaintext;
                $descr = Parse::reduceDescr($descr1 . $descr2);

                $free = true;

                $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
                VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");
                $i++;
            }
        }
        fwrite($fOpen,'Received records from Tvoyamoskva.com: '.$i);
        fwrite($fOpen,'  Execution time: '.(time() - $startTime).' secs'."\n");
    }

    /**
     * Парсинг сайта moscoviti.ru
     */
    public static function parseMV()
    {
        global $mysqli;
        global $fOpen;

        $html = file_get_html('https://moscoviti.ru/raspisanie/');
        $i = 0;
        $site = 'Moscoviti';
        $startTime = time();
        $cancel = false;

        foreach ($html->find('div.elementor-text-editor.elementor-clearfix') as $divBig) {
            $found = $divBig->find('span.tg-block', 0);
            if ($found !== null) {
                foreach ($divBig->find('span.tg-block') as $div) {
                    $link = $div->find('a', 0)->href;
                    if (mb_substr($link, 0, 3) != 'htt') {
                        $link = 'https://moscoviti.ru/product/' . $link;
                    }

                    $htmlInner = file_get_html($link);
                    if(!$htmlInner) continue;
                    $dateStr = $htmlInner->find('tbody p', 0)->innertext;
                    $day = (explode(' ', $dateStr)[1]);
                    if (!ctype_digit($day)) {
                        continue;
                    }

                    $date = Parse::formDateMonth($dateStr, $day);
                    $time = explode('в ', $dateStr)[1] . ':00';
                    $option = Parse::checkDate($date);
                    if ($option == 1) {
                        break;
                    } elseif ($option == 2) {
                        $cancel = true;
                        break;
                    }
                    $title = $div->find('a', 0)->plaintext;
                    $url = explode('url(', ($htmlInner->find('div.elementor-cta__bg.elementor-bg', 0)->getAttribute('style')));
                    $img_url = mb_substr($url[1], 0, -2);
                    $url_tmp = Parse::saveImgFile($img_url);
                    if ($url_tmp != '0') {
                        $img_url = $url_tmp;
                    }

                    if (($htmlInner->find('tbody p', 3)->innertext) == 'бесплатная') {
                        $free = true;
                    } else {
                        $free = false;
                    }

                    $guide = $htmlInner->find('tbody p', 4)->innertext;
                    $guideStr = explode(' ', $guide);
                    $guide = $guideStr[1] . ' ' . $guideStr[0];
                    $guideId = Parse::getGuideId($guide);

                    $descr = $htmlInner->find('div[role=tabpanel] p', 0)->innertext;
                    $descr = Parse::reduceDescr($descr);

                    $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
                     VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");
                    $i++;
                }
            }
            if ($cancel === true) break;
        }
        fwrite($fOpen, 'Received records from Moscoviti.ru: ' . $i);
        fwrite($fOpen, '  Execution time: ' . (time() - $startTime) . ' secs' . "\n");

    }

    /**
     * Парсинг сайта moskvahod.ru
     */
    public static function parseMH() {
        global $mysqli;
        global $fOpen;

        $html = file_get_html('https://www.moskvahod.ru/month/%D0%A0%D0%B0%D1%81%D0%BF%D0%B8%D1%81%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BF%D1%80%D0%BE%D0%B3%D1%83%D0%BB%D0%BE%D0%BA-%D0%BF%D0%BE-%D0%9C%D0%BE%D1%81%D0%BA%D0%B2%D0%B5/%D0%BF%D0%B5%D1%88%D0%B5%D1%85%D0%BE%D0%B4%D0%BD%D1%8B%D0%B5-%D1%8D%D0%BA%D1%81%D0%BA%D1%83%D1%80%D1%81%D0%B8%D0%B8/');
        $i = 0;
        $site = 'Moskvahod';
        $free = false;
        $startTime = time();

        $currentMonthStr = $html->find('span.calendar-header-month-current',0)->innertext;
        $currentMonthDate = Parse::formDateMonth($currentMonthStr,1);
        $monthStr = date('m',strtotime('today + '.DAYS_SHIFT.' day'));
        if(substr($currentMonthDate,5,2) != $monthStr){
            $nextMonthUrl = $html->find('a.calendar-header-month-next',0)->getAttribute('data-month');
            $nextMonthUrl = 'https://www.moskvahod.ru/month/?month='.$nextMonthUrl;
            $html = file_get_html($nextMonthUrl);
        }

        foreach($html->find('div[data-info]') as $div){
           $dataArr = explode(' ',$div->getAttribute('data-info'));
           if ($dataArr[0] == "") continue;
           $guide = $dataArr[1].' '.$dataArr[0];
           $guideId = Parse::getGuideId($guide);
           $day = $dataArr[2];
           $date = Parse::formDateMonth(' '.$dataArr[3],$day);
           $time = $dataArr[5].':00';

           $option = Parse::checkDate($date);
           if ($option == 1) continue;
           elseif($option == 2)  break;

           $img_url = $div->getAttribute('data-img');
           $url_tmp = Parse::saveImgFile($img_url);
           if ($url_tmp != '0'){
               $img_url = $url_tmp;
           }
           $link = $div->getAttribute('data-link');
           if(!get_headers($link, 1)){
            continue;
            }
           $title = $div->getAttribute('data-title');

           $htmlInner = file_get_html( $link );
           $descr = ($htmlInner->find('div.catalog-body__about p',0)->plaintext).'...';
           $descr = Parse::reduceDescr($descr);

           $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
           VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");
           $i++;
        }
        fwrite($fOpen,'Received records from Moskvahod.ru: '.$i);
        fwrite($fOpen,'  Execution time: '.(time() - $startTime).' secs'."\n");
    }

    /**
     * Парсинг сайта moscowsteps.com
     */
    public static function parseMSt(){
        global $mysqli;
        global $fOpen;

        $html = file_get_html('https://moscowsteps.com/timetable');
        $i = 0;
        $startTime = time();
        $free = false;
        $site = 'Moscowsteps';

        foreach($html->find('td[id]') as $tableDay){
            $date= $tableDay->getAttribute('data-fulldate');
            $option = Parse::checkDate($date);
            if ($option == 1){
                continue;
            }elseif($option == 2){
                break;
            }

            foreach($tableDay->find('span.dop') as $div){
                $time = $div->find('b.time_ex',0)->plaintext;
                $title = $div->find('a',0)->plaintext;
                $link = $div->find('a',0)->href;
                $link = 'https://moscowsteps.com/'.$link;
                $htmlInner = file_get_html( $link );
                $img_url = $htmlInner->find('td.mmm img',0)->getAttribute('src');
                $img_url = 'https://moscowsteps.com/'.$img_url;
                $url_tmp = Parse::saveImgFile($img_url);
                if ($url_tmp != '0'){
                    $img_url = $url_tmp;
                }
                $guideStr = explode(' ',$htmlInner->find('a[href=/guides]',0)->innertext);
                $guide = $guideStr[1].' '.$guideStr[0];
                $guideId = Parse::getGuideId($guide);
                $descr = $htmlInner->find('div[style=text-align: justify!important;] span',0)->plaintext;
                if (strtok($descr,'!') == 'ВНИМАНИЕ'){
                    $descr = $htmlInner->find('div[style=text-align: justify!important;] span',1)->plaintext;
                }
                $descr = Parse::reduceDescr($descr);

                $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide_id`, `img_url`, `free`, `link`, `descr`)
                VALUES ('$site','$date','$time','$title','$guideId','$img_url','$free','$link','$descr')");
                $i++;
            }
        }

        fwrite($fOpen,'Received records from Moscowsteps.com: '.$i);
        fwrite($fOpen,'  Execution time: '.(time() - $startTime).' secs'."\n");
    }

    /**
     * Функция формирует дату в формате yyyy-mm-dd из текстового месяца на кириллице и даты
     * @param $dateStr
     * @param $day
     * @return string
     */
    public static function formDateMonth($dateStr, $day) {
        if (strlen($day) == 1){
            $day = '0'.$day;
        }
        $arrDate = getdate(strtotime('today'));
        $year = $arrDate['year'];
        if ((($arrDate['yday'] + DAYS_SHIFT) >= 365) && $day <= DAYS_SHIFT) $year = $year + 1;
        $dateStr = mb_strtolower($dateStr);

        if (strpos($dateStr,'январ') !== false) {$date = $year.'-01-'.$day;
            }elseif(strpos($dateStr,'феврал') !== false) {$date = $year.'-02-'.$day;
            }elseif(strpos($dateStr,'март') !== false) {$date = $year.'-03-'.$day;
            }elseif(strpos($dateStr,'апрел') !== false) {$date = $year.'-04-'.$day;
            }elseif(strpos($dateStr,'мая') !== false || strpos($dateStr,'май') !== false) {$date = $year.'-05-'.$day;
            }elseif(strpos($dateStr,'июн') !== false) {$date = $year.'-06-'.$day;
            }elseif(strpos($dateStr,'июл') !== false) {$date = $year.'-07-'.$day;
            }elseif(strpos($dateStr,'август') !== false) {$date = $year.'-08-'.$day;
            }elseif(strpos($dateStr,'сентябр') !== false) {$date = $year.'-09-'.$day;
            }elseif(strpos($dateStr,'октябр') !== false) {$date = $year.'-10-'.$day;
            }elseif(strpos($dateStr,'ноябр') !== false) {$date = $year.'-11-'.$day;
            }elseif(strpos($dateStr,'декабр') !== false) {$date = $year.'-12-'.$day;
            }else {$date = $year.'-01-01';}
        return($date);
    }

    /**
     * Функция возвращает id гида по его имени и фамилии, если не найден - добавляет в базу
     * @param $guide
     * @return int|mixed
     */
    public static function getGuideId($guide) {
        global $mysqli;
        global $fOpen;
        
        $found = false;
        $maxId = 0;
        $guideId = 0;

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
            fwrite($fOpen,'  Added guide: '.$guide.' (id='.$guideId.')'."\n");
        }
        return($guideId);
    }

    /**
     * Функция обрезает описание экскурсии до глобального параметра DESCRIP_MAXLENGTH и ставит в конце три точки
     * @param $descr
     * @return mixed|string
     */
    public static function reduceDescr($descr) {
        if(mb_strlen($descr)>DESCRIP_MAXLENGTH){
            $arr = explode(' ',mb_substr($descr,0,DESCRIP_MAXLENGTH));
            array_pop($arr);
            return(implode(' ', $arr).'...');
        }else{return($descr);
        } 
    }

    /**
     * Функция возвращает 0 чтобы цикл продолжился, 1 чтобы continue, 2 чтобы break в зависимости от входной даты
     * @param $date
     * @return int
     */
    public static function checkDate($date){
        if((strtotime($date) == strtotime('today + '.DAYS_SHIFT.' day'))){
            return(0); 
        }else if ((strtotime($date) < strtotime('today + '.DAYS_SHIFT.' day'))){
            return(1);
        }else {
            return(2);
        }
    }

    /**
     * Функция копирует фотографию на локальный диск в папку, указанную в глобальном параметре $imgDir,
     * прибавляя к имени файла случайное число
     * @param $img_url
     * @return false|string
     */
    public static function saveImgFile ($img_url){
        global $imgDir;

        $filePath = $imgDir.substr(microtime('as_float'),12).'_'.basename($img_url);
        
        if (file_put_contents($filePath, file_get_contents($img_url))){ 
            return (substr($filePath,3));
        }else return '0';
    }

}
