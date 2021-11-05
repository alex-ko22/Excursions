 <?php
 /**
  * Скрипт запроса к базе по параметрам из формы
  */
    header('Content-type: text/html; charset=utf-8');
    require_once('db.php');
    global $mysqli;

    if(($site = $_POST['site']) != 'All'){
        $site = ' AND `site` = "'.$site.'"';
     }else $site = '';

    if(($free = $_POST['type']) == 'Free') {
        $free = ' AND `free` = "1"';
    }elseif($free == 'Notfree') {
        $free = ' AND `free` = "0"';
    }else {
        $free = '';
    }

    if (($date = $_POST['date']) == '10'){
        $date = '`date` BETWEEN CURRENT_DATE AND (DATE_ADD(CURRENT_DATE, INTERVAL '.$date.' DAY))';     
    }else{
        $date = '`date` = DATE_ADD(CURRENT_DATE(), INTERVAL '.$date.' DAY)';
    }
   
    if(($guide = $_POST['guide']) != 'All'){
        $guide = ' AND `guide_id` = '.$guide;
    } else $guide = '';

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
