<?php
class Parse{

    public static function formDateMonth($dateStr,$day) {

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
            
        return($date);
    }
}
?>