<?php
header('Content-Type: application/json; charset=utf-8');
$db = new SQlite3('user-store.db');
$request = $_GET["q"];
$db -> enableExceptions(true);

//prints error
//gets error message
function printErr($message){
    print_r(json_encode(
        ['status' => 'error',
         'message' => $message]));
}

//prints result
//gets result from sql request and error message
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
        printErr($error);
    }
}
}

//catches errors + prints by result function
//gets sqlite request and boolean if result needs to show
function catchErr($stmt, $die){
    try{
        $result = $stmt -> execute();
        if($die){
            result($result, "No result");
        }
    }
    catch(Exception $e){
        printErr($e->getMessage());
        return false;
    }
    return true;
}

//prints last inserted row from table
//gets tableName
$printLastRow = function ($tableName) use ($db) {
    $lastid = $db->lastInsertRowid();
    if($stmt = $db -> prepare('SELECT * FROM ' . $tableName . ' WHERE id=:p')) {
        $stmt->bindValue(':p', $lastid);
        catchErr($stmt, true);
    }
};

$printLastRowWithId = function ($tableName, $id) use ($db) {
    if($stmt = $db -> prepare('SELECT * FROM ' . $tableName . ' WHERE id=:p')) {
        $stmt->bindValue(':p', $id);
        if(catchErr($stmt, true))
            return true;
        else return false;
    }
};

switch ($request) {
    
    case "searchI":
    //works
        $param = "%{$_GET["p"]}%";
        if($stmt = $db -> prepare('SELECT * FROM Interest WHERE description LIKE :p')) {
            $stmt->bindValue(':p', $param);
            catchErr($stmt, true);
        }
        else{
            printErr("Error");
        }
    break;
    
    case "newI":
    //works
        $param = "{$_GET["p"]}";
        if($stmt = $db -> prepare('INSERT INTO Interest VALUES(NULL,:p)')){
            $stmt->bindValue(':p', $param);
            if(catchErr($stmt, false)){
                $printLastRow('Interest');
            }
        }
        else{
            $errormsg['message']='Error';
            print_r(json_encode($errormsg));
        }
    break;

    case "updateI":
    //works
        $param = "{$_GET["p"]}";
        $id = "{$_GET["id"]}";
        if($stmt = $db -> prepare('UPDATE Interest SET description=:p WHERE id=:id')){
            $stmt->bindValue(':p', $param);
            $stmt->bindValue(':id', $id);
            if(catchErr($stmt, false)){
                $printLastRowWithId('Interest', $id);
            }
        }
        else{
            $errormsg['message']='Error';
            print_r(json_encode($errormsg));
        }
    break;   
    
    //works
    case "deleteI":
        $param = "{$_GET["p"]}";
        if($stmt = $db -> prepare('DELETE FROM Interest WHERE id=:p')){
            $stmt->bindValue(':p', $param);
            if($printLastRowWithId('Interest', $param))
                {
                    catchErr($stmt, false);
                }
        }
        else{
            $errormsg['message']='Error';
            print_r(json_encode($errormsg));
        }
    break;

    //works
    case "viewI":   
        $param = "{$_GET["p"]}";
        if($stmt = $db -> prepare('SELECT * FROM Interest WHERE id=:p')) {
               $stmt->bindValue(':p', $param);
               catchErr($stmt, true);
        }
        else{
          printErr("Error");
        }
    break;


    case "viewSingleP": 
        $param = "{$_GET["p"]}";
        if($stmt = $db -> prepare('SELECT * FROM Person WHERE id=:p')) {
            $stmt->bindValue(':p', $param);
            catchErr($stmt, true);
        }
        else{
            printErr("Error");
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