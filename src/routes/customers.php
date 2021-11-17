<?php
// Brings the Request and Response classes to use its in the code
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;// Creates new Slim App instance

// ...
$app->get('/customers', function(Request $request, Response $response){
    $query = "SELECT * FROM customers;";
    try {
        $dbObj = new apiDB();
        $dbObj = $dbObj->connectToDB();
        $result = $dbObj->query($query);// Executes query string to bring the data

        if ($result->rowCount() > 0){
            $customers = $result->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($customers);
        }
        else {
            echo "There are no registered Customers yet";
        }
        $result = null;
        $dbObj = null;
    }
    catch(PDOException $err){
        echo "Error: " . $err->getMessage();
    }
});
