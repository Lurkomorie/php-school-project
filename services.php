<?php
header('Content-Type: application/json; charset=utf-8');
$db = new SQlite3('user-store.db');
$errormsg = ['status' => 'error',
             'message' => ''];
$request = $_GET["q"];

function result($result, $error){
    if($result != false){
    $finalResult = [];
    while ($row = $result -> fetchArray(SQLITE3_ASSOC)) {
        $finalResult[] = json_encode($row,JSON_UNESCAPED_UNICODE);
    }
    if (!empty($finalResult)) {
        foreach($finalResult as $row) {
            print_r($row);
        }
    } else {
        $errormsg['message']=$error;
        print_r(json_encode($errormsg));
    }
    }
    else {
        $errormsg['message']='Error';
        print_r(json_encode($errormsg));
    }

}

switch ($request) {
    case "searchI":
        $results = $db -> query('SELECT * FROM Interest WHERE description="'. $_GET["p"].'"');
        result($results, "Empty");
    break;
    
    case "newI":
        $results = $db -> query('INSERT INTO Interest VALUES(NULL,"'. $_GET["p"]. '")');
        result($results,"Cannot create");
    break;

    case "updateI":
        $result = $db -> query('UPDATE Interest SET description="' . $_GET["p"] . '" WHERE id=' . $_GET["id"]);
        if($result){
            print_r("TRUE");
            return true;
        }
        else {
            $errormsg['message']="Error Inserting";
            print_r(json_encode($errormsg));
        }
    break;   
    
    case "deleteI":
        $result = $db -> query('DELETE FROM Interest WHERE id=' . $_GET["p"]);
        if($result){
            print_r("TRUE");
            return true;
        }
        else {
            $errormsg['message']="Error Deleting";
            print_r(json_encode($errormsg));
        }
    break;

    case "viewI":   
        $results = $db -> query('SELECT * FROM Interest WHERE id="'. $_GET["p"].'"');
        $finalResult = [];
        while ($row = $results -> fetchArray(SQLITE3_ASSOC)) {
            $finalResult[] = json_encode($row,JSON_UNESCAPED_UNICODE);
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

    case "viewSingleU": 
        $results = $db -> query('SELECT * FROM Person WHERE id="'. $_GET["p"].'"');
        $finalResult = [];
        while ($row = $results -> fetchArray(SQLITE3_ASSOC)) {
            $finalResult[] = json_encode($row,JSON_UNESCAPED_UNICODE);
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
    
    case "viewAllU": 
        $results = $db -> query('SELECT * FROM Person');
        $finalResult = [];
        while ($row = $results -> fetchArray(SQLITE3_ASSOC)) {
            $finalResult[] = json_encode($row,JSON_UNESCAPED_UNICODE);
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
    
    case "newU":
        $statement = $db->prepare('INSERT INTO Person(firstName, lastName, phone, active, age) VALUES(:firstName, :lastName, :phone, :active, :age)');
        if($statement){
            $statement->bindValue(':firstName', $_GET['fname']);
            $statement->bindValue(':lastName', $_GET['lname']);
            $statement->bindValue(':phone', $_GET['phone']);
            $statement->bindValue(':active', $_GET['active']);
            $statement->bindValue(':age', $_GET['age']);
            $result = $statement->execute();
            }
        if($result){
            print_r("TRUE");
            return true;
        }
        else {
            $errormsg['message']="Error Inserting";
            print_r(json_encode($errormsg));
        }
    break;

}