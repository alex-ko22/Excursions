 <?php
    // Функция формирования запроса к базе
    
    header('Content-type: text/html; charset=utf-8');
    require_once('db.php');
    global $mysqli;

    if(($site = $_POST['site']) != ' '){
        $site = ' AND `site` = "'.$site.'"';
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
    
    if (($date = $_POST['date']) == '100'){
        $date = '`date` BETWEEN CURRENT_DATE AND (DATE_ADD(CURRENT_DATE, INTERVAL '.$date.' DAY))';     
    }else{
        $date = '`date` = DATE_ADD(CURRENT_DATE(), INTERVAL '.$date.' DAY)';
    }
   
    if(($guide = $_POST['guide']) != ' '){
        $guide = ' AND `guide_id` = '.$guide;
    } 

    // Формируем запрос 
    $where = $date.$guide.$site.$free.' ORDER BY `date`, `time`';

    $result = mysqli_query($mysqli,"SELECT *
     FROM `excursion` LEFT JOIN `guides` ON `excursion`.`guide_id` = `guides`.`id`
        WHERE $where");
    
    $recs = [];
    while( $row = $result->fetch_assoc() ){
        $recs[] = $row;
    }
    
    echo json_encode($recs, JSON_UNESCAPED_UNICODE); 
