<?php
header('Content-Type: application/json; charset=utf-8');
$db = new SQlite3('user-store.db');
$request = $_GET["q"];
$db -> enableExceptions(true);

//prints error
function printErr($message){
    print_r(json_encode(
        ['status' => 'error',
         'message' => $message]));
}

//prints result
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
function catchErr($stmt){
    try{
        $result = $stmt -> execute();
        result($result, "No result");
    }
    catch(Exception $e){
        printErr($e->getMessage());
    }
}

//prints last inserted row from table
$printLastRow = function ($tableName) use ($db) {
    if($stmt = $db -> prepare('SELECT * FROM ' . $tableName . ' WHERE id=:p')) {
        $stmt->bindValue(':p', 5);
        catchErr($stmt);
    }
};

switch ($request) {
    
    case "searchI":
    //works
        $param = "%{$_GET["p"]}%";
        if($stmt = $db -> prepare('SELECT * FROM Interest WHERE description LIKE :p')) {
            $stmt->bindValue(':p', $param);
            catchErr($stmt);
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
            catchErr($stmt);
            $printLastRow('Interest');
        }
        else{
            $errormsg['message']='Error1';
            print_r(json_encode($errormsg));
        }
    break;

    case "updateI":
        if($stmt = $db -> prepare('UPDATE Interest SET description="' . $_GET["p"] . '" WHERE id=' . $_GET["id"])){
            $resultQ = @$stmt -> execute();
            @result($resultQ, "Empty");
        }
        else{
            $errormsg['message']='Error';
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