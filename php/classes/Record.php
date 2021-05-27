<?php

class Record {
    public static function getTodayRecs(){
        
        global $mysqli;

        $result = $mysqli->query("SELECT * FROM `excursion` WHERE `date` = CURRENT_DATE()");
        $recs = [];
        while( $row = $result->mysqli_fetch_assoc() ){
          $recs[] = $row;
        }
        
        echo json_encode($recs); 
    }
}

?>