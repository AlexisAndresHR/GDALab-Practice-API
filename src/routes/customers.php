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

/**
 * To register a new Customer
 */
$app->post('/customers/new', function(Request $request, Response $response){
    // Puts into variables the data received in request body
    $dni = $request->getParam('dni');
    $idReg = $request->getParam('id_reg');
    $idCom = $request->getParam('id_com');
    $email = $request->getParam('email');
    $name = $request->getParam('name');
    $lastName = $request->getParam('last_name');
    $address = $request->getParam('address');
    $dateReg = $request->getParam('date');
    $dateReg = date("Y-m-d H:i:s", strtotime($dateReg));// Convert received date string into datetime
    //$status = $request->getParam('status');

    try {
        // Before execute the query on database, prepare statement by binding parameters (:param)
        $insertQuery = "INSERT INTO customers (dni, id_reg, id_com, email, name, last_name, address, date_reg) 
                VALUES (:dni, :id_reg, :id_com, :email, :name, :last_name, :address, :date_reg)";
        
        $dbObj = new apiDB();// Creates an instance of DB class
        $dbObj = $dbObj->connectToDB();
        $result = $dbObj->prepare($insertQuery);// Prepares the query string to be executed
        
        // bindParam function validates user input (API request in this case) before sending it to the DB.
        $result->bindParam(':dni', $dni);
        $result->bindParam(':id_reg', $idReg);
        $result->bindParam(':id_com', $idCom);
        $result->bindParam(':email', $email);
        $result->bindParam(':name', $name);
        $result->bindParam(':last_name', $lastName);
        $result->bindParam(':address', $address);
        $result->bindParam(':date_reg', $dateReg);
        
        $result->execute();// Executes the validated query on the database;

        echo json_encode("The customer has been saved.");
        
        // Resets the variables and close the connection to database
        $result = null;
        $dbObj = null;
    }
    catch(PDOException $err){
        error_log('Register Customer PDOException - ' . $err->getMessage(), 0);// Sends the error message to the server log file
        http_response_code(500);// Returns HTTP 500 error and stops the execution...
        die('Error: Register process failed. Please report it to Admin.');// ... showing this message
    }
});
