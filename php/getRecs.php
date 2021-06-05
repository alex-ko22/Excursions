 <?php
    // Функция формирования запроса к базе
    
    header('Content-type: text/html; charset=utf-8');
    require_once('classes/Parse.php');
    require_once('db.php');

    /* switch($_POST['site']){
        case 0:
            $site = '';
            break;
        case 1:
            $site = ' AND `site` = "Mosstreets"';
            break;
        case 2:
            $site = ' AND `site` = "Moscowwalking"';
            break;
        case 3:
            $site = ' AND `site` = "Tvoyamoskva"';
            break;
        case 4:
            $site = ' AND `site` = "Moscoviti"';
            break;
        case 5:
            $site = ' AND `site` = "Moskvahod"';
            break;
    } */
    //$site = $_POST['site'];
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
   
    $date = $_POST['date'];   
    
    if ($date == '100'){
        $dateStr = '`date` BETWEEN CURRENT_DATE AND (DATE_ADD(CURRENT_DATE, INTERVAL '.$date.' DAY))';     
    }else{
        $dateStr = '`date` = DATE_ADD(CURRENT_DATE(), INTERVAL '.$date.' DAY)';
    }
     
    $guide = $_POST['guide'];
   
    if($guide == '0'){
        $guideStr = '';
    }else{
        $guideStr = ' AND `guide_id` = '.$guide;
    } 

    // Формируем запрос 
    $where = $dateStr.$guideStr.$site.$free.' ORDER BY `date`, `time`';

    $result = mysqli_query($mysqli,"SELECT *
     FROM `excursion` LEFT JOIN `guides` ON `excursion`.`guide_id` = `guides`.`id`
        WHERE $where");
    
    $recs = [];
    while( $row = $result->fetch_assoc() ){
        $recs[] = $row;
    }
    
    echo json_encode($recs, JSON_UNESCAPED_UNICODE); 

?>