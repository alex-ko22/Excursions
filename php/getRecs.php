 <?php
    // Функция формирования запроса к базе
    
    header('Content-type: text/html; charset=utf-8');
    require_once('db.php');

    if(array_key_exists('site1',$_POST) && !(array_key_exists('site2',$_POST))){
        $site = ' AND `site` = "Mosstreets.ru"';
    }elseif(array_key_exists('site2',$_POST) && !(array_key_exists('site1',$_POST))){
        $site = ' AND `site` = "Moscowwalking.ru"';
    }elseif(!(array_key_exists('site2',$_POST)) && !(array_key_exists('site1',$_POST))){
        $site = ' AND `site` = "Zero"';    
    }else{
        $site = '';
    }

    if(array_key_exists('free1',$_POST) && !(array_key_exists('free2',$_POST))){
        $free = ' AND `free` = "1"';
    }elseif(array_key_exists('free2',$_POST) && !(array_key_exists('free1',$_POST))){
        $free = ' AND `free` = "0"';
    }elseif(!(array_key_exists('free2',$_POST)) && !(array_key_exists('free1',$_POST))){
        $free = ' AND `free` = "Zero"';    
    }else{
        $free = '';
    }
   
    $date = $_POST['date'];
    $guide = $_POST['guide'];
   
    if($guide == '0'){
        $guideStr = '';
    }else{
        $guideStr = ' AND `guide_id` = '.$guide;
    } 

    // Формируем запрос 

    $where = '`date` BETWEEN CURRENT_DATE AND (DATE_ADD(CURRENT_DATE, INTERVAL '.$date.' DAY))'.$guideStr.$site.$free.' ORDER BY `date`, `time`';

    $result = mysqli_query($mysqli,"SELECT *
     FROM `excursion` LEFT JOIN `guides` ON `excursion`.`guide_id` = `guides`.`id`
        WHERE $where");
    
    $recs = [];
    while( $row = $result->fetch_assoc() ){
        $recs[] = $row;
    }
    
    echo json_encode($recs, JSON_UNESCAPED_UNICODE); 

?>