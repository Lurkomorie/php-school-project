<?php
 header('Content-Type: application/json');
 $db = new SQlite3('user-store.db');
 $errormsg = '{"status":"error","message":"Empty"}';
 try{
 $results = $db->query('SELECT * FROM Interest WHERE description="' . htmlspecialchars($_GET["desc"]) . '"');
 $finalResult = [];
    while ($row = $results->fetchArray(SQLITE3_ASSOC))
    {
        $finalResult[] = json_encode($row);
    }
if(!empty($finalResult)){
    foreach ($finalResult as $row) {
        print_r($row);
    }
}
else{
    print_r($errormsg);
}
}
catch(Exception $e){
    print_r('{"status":"error","message":"' . $db->lastErrorMsg() . '"}');
}



