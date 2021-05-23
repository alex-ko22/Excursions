<?php

class Parse {
    public static function parseMs() {

        global $mysqli;
        
        $html = file_get_html('https://mosstreets.ru/schedule/');
        $i=0;
        $site = 'Mossreets.ru';

        foreach($html->find('div.trio') as $div){
            $divmini = $div->find('div.mini',0);         
            $url = explode('image:',$divmini = $div->find('div.mini',0)->getAttribute('style'));
            $img_url = $url[1];
            $link = $div->find('p.trio_header a',0)->href;
            
            $title = $div->find('div.desc p',0)->plaintext;
            $htmlInner = file_get_html( $link );
            $descr = $htmlInner->find('div.entry p', 1)->plaintext; 
            $dateStr= $div->find('div.desc p',1)->plaintext;
            $date = '2021-'.substr($dateStr,3,2).'-'.substr($dateStr,0,2);
            $time = substr($dateStr,16,5).':00';
            $price = $div->find('div.desc p',2)->plaintext;
            if( $price == 'бесплатная экскурсия') {
                $free = true;
            } else {$free = false;
                }    
            $guide = $div->find('span.guide',0)->plaintext;

            $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide`, `img_url`, `free`, `link`, `descr`)
            VALUES ('$site','$date','$time','$title','$guide','$img_url','$free','$link','$descr')");
            
            $i=$i+1;
            if ($i>7) {break;} 
        } 
    }

    public static function parseMw() {

        global $mysqli;
        
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

            $site = 'moscowwalking.ru';

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

                $mysqli->query("INSERT INTO `excursion`(`site`, `date`, `time`, `title`, `guide`, `img_url`, `free`, `link`, `descr`)
                    VALUES ('$site','$date','$time','$title','$guide','$img_url','$free','$link','$descr')");
                
            }
            $i=$i+1;
            if ($i>1) {break;}
        }
 
    }

   
}

?>