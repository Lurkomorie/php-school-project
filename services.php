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

/**
 * cbatches errors + prints by result function
 * 
 * @params
 * $stmt -  database connection
 * $die - boolean - TRUE if we need to kill the programmer before
 * 
 * @returns
 *   TRUE/FALSE
 **/
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

//prints last row from table
//gets tableName and id
$printRowWithId = function ($tableName, $id) use ($db) {
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
                $printRowWithId('Interest', $id);
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
            if($printRowWithId('Interest', $param))
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



    //works
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
    
    //works
    case "viewAllP": 
        if($stmt = $db -> prepare('SELECT * FROM Person')) {
            catchErr($stmt, true);
        }
        else{
        printErr("Error");
        }
    break;
    
    //works
    case "newP":
        $firstName = "{$_GET["firstName"]}";
        $lastName = "{$_GET["lastName"]}";
        $phone = "{$_GET["phone"]}";
        $active = "{$_GET["active"]}";
        $age = "{$_GET["age"]}";

        if($stmt = $db->prepare('INSERT INTO Person(firstName, lastName, phone, active, age) VALUES(:firstName, :lastName, :phone, :active, :age)')){
        $stmt->bindValue(':firstName', $firstName);
        $stmt->bindValue(':lastName', $lastName);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':active', $active);
        $stmt->bindValue(':age', $age);
            if(catchErr($stmt, false)){
                $printLastRow('Person');
            }
        }
        else{
            $errormsg['message']='Error';
            print_r(json_encode($errormsg));
        } 
    break;

    //works
    case "deleteP":
        $id = "{$_GET["p"]}";
        if($stmt = $db -> prepare('DELETE FROM Person WHERE id=:p')){
            $stmt->bindValue(':p', $id);
            if($printRowWithId('Person', $id))
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
    case "editP":
        $id = "{$_GET["p"]}";
        $firstName = "{$_GET["firstName"]}";
        $lastName = "{$_GET["lastName"]}";
        $phone = "{$_GET["phone"]}";
        $active = "{$_GET["active"]}";
        $age = "{$_GET["age"]}";

        if($stmt = $db->prepare('UPDATE Person SET firstName=:firstName, lastName=:lastName, phone=:phone, active=:active, age=:age WHERE id=:id')){
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':firstName', $firstName);
        $stmt->bindValue(':lastName', $lastName);
        $stmt->bindValue(':phone', $phone);
        $stmt->bindValue(':active', $active);
        $stmt->bindValue(':age', $age);
            if(catchErr($stmt, false)){
                $printRowWithId('Person', $id);
            }
        }
        else{
            $errormsg['message']='Error';
            print_r(json_encode($errormsg));
        } 
    break;

    case "searchP":
        $param = "{$_GET"
    break;

}