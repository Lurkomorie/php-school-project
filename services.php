<?php
header('Content-Type: application/json; charset=utf-8');
$db = new SQlite3('user-store.db');
$errormsg = ['status' => 'error',
             'message' => ''];
$request = $_GET["q"];

switch ($request) {
    case "searchInterest":
        $results = $db -> query('SELECT * FROM Interest WHERE description="'. $_GET["desc"].'"');
        $finalResult = [];
        while ($row = $results -> fetchArray(SQLITE3_ASSOC)) {
            $finalResult[] = json_encode($row);
        }
        if (!empty($finalResult)) {
            foreach($finalResult as $row) {
                print_r($row);
            }
        } else {
            $errormsg['message']="Empty";
            print_r(json_encode($errormsg));
        }
    break;
    
    case "newInterest":
        $result = $db -> query('INSERT INTO Interest VALUES("'. $_GET["desc"]. '")');
        
        
}