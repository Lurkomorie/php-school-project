<?php
header('Content-Type: application/json; charset=utf-8');
$db = new SQlite3('user-store.db');
$request = $_GET["q"];
$db -> enableExceptions(true);
$resultA = array();

function getResult(){
    global $resultA;
    echo json_encode($resultA,JSON_UNESCAPED_UNICODE);
}

function goback()
{
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}

//prints error
//gets error message
function printErr($message){
    global $resultA;
    $row = (
        ['status' => 'error',
         'message' => $message]);
    $resultA[] = $row;            
}

//prints result
//gets result from sql request and error message
function result($result, $error){
    global $resultA;
    if($result != false){
        $finalResult = [];
    while ($row = $result -> fetchArray(SQLITE3_ASSOC)) {
        $finalResult[] = $row;
    }
    if (!empty($finalResult)) {
        foreach($finalResult as $row) {
            $resultA[] = $row;
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

function findInterestId($p, $db){
    if($stmt = $db -> prepare('SELECT id FROM Interest WHERE description LIKE :p')) {
        $stmt->bindValue(':p', $p);
        $result = $stmt->execute();
        $finalResult = [];
        if($result != false){
        while ($row = $result -> fetchArray(SQLITE3_NUM)) {
            $finalResult[] = $row;
        }
        return $finalResult;
    }
        else return false;
}
    else{
        printErr("Error");
    }
};

function findPersonsIdByInterestId ($p,$db){
    $perArr = [];
    $interestsId = findInterestId($p, $db); 
    if($interestsId!=false){
    for($i = 0; $i < count($interestsId); $i++){
    if($stmt = $db -> prepare('SELECT personId FROM Person_Interests WHERE interestId=:p')) {
        $temp = $interestsId[$i];
        $pp = $temp[0];
        $stmt->bindValue(':p', $pp);    
        $result = $stmt->execute();  
        while ($row = $result -> fetchArray(SQLITE3_NUM)) {
            $perArr[] = $row;
        }
    }
    else{
        printErr("Error");
    }
    return $perArr;
    }
}
};

function findPersonsById($arr,$db){
    for($i = 0; $i < count($arr); $i++){
        if($stmt = $db -> prepare('SELECT * FROM Person WHERE id=:p')) {
            $index = $arr[$i];
            $stmt -> bindValue(':p',$index[0]);
            catchErr($stmt, true);
    }
}
}

/*function countRowsInTable($tableName){
    if($stmt = $db -> prepare('SELECT COUNT(*) FROM :p')) {
        $stmt->bindValue(':p', $tableName);
        $result = $stmt->execute();
        while ($row = $result -> fetchArray(SQLITE3_ASSOC)) {
            return $row;
        }
    }
    else{
        printErr("Error");
    }
}*/



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
            printErr("Error");
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
            printErr("Error");
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
            printErr("Error");
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
            printErr("Error");
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
                goback();
        }
        else{
            printErr("Error");
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
            printErr("Error");
        } 
    break;

    case "searchP":
        $param = "%{$_GET["p"]}%";
        if($stmt = $db->prepare('SELECT * FROM Person WHERE (firstName like :firstName OR lastName like :lastName OR phone like :phone)')){
            $stmt->bindValue(':firstName', $param);
            $stmt->bindValue(':lastName', $param);
            $stmt->bindValue(':phone', $param);
            catchErr($stmt, true);
        }
        else{
            printErr("Error");
        }
        $param = $_GET['p']; 
        $arr = findPersonsIdByInterestId($param,$db);
        @findPersonsById($arr,$db);

        break;

    case "getUserInterest":
        //works
        $param = "{$_GET["p"]}";
        if($stmt = $db -> prepare('SELECT Interest.description FROM Interest inner join Person_Interests on Interest.id = Person_Interests.interestId WHERE Person_Interests.personId = :p')) {
            $stmt->bindValue(':p', $param);
            catchErr($stmt, true);
        }
        else{
            printErr("Error");
        }
        break;
}

getResult();