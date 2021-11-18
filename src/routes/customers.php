<?php
// Brings the Request and Response classes to use its in the code
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;// Creates new Slim App instance

/**
 * To register a new Customer
 * -> POST method
 */
$app->post('/customers/register', function(Request $request, Response $response){
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
        $dbObj = new apiDB();// Creates an instance of DB class
        $dbObj = $dbObj->connectToDB();// Makes the connection through function

        // Validates the FK's of communes and regions received in the requet data
        $validationQuery = "SELECT * FROM communes 
                INNER JOIN regions ON communes.id_reg = regions.id_reg
                WHERE communes.id_com = '$idCom' AND communes.id_reg = '$idReg';";// Union query to verify that exists a commune with the given ids and the association with a region
        $validationComReg = $dbObj->query($validationQuery);// Executes query string to bring the data

        if ($validationComReg->rowCount() > 0){
            
            // Before execute the query on database, prepare statement by binding parameters (:param)
            $insertQuery = "INSERT INTO customers (dni, id_reg, id_com, email, name, last_name, address, date_reg) 
                    VALUES (:dni, :id_reg, :id_com, :email, :name, :last_name, :address, :date_reg)";

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
            $validationComReg = null;
            $result = null;
            $dbObj = null;

        }
        else {
            echo "The given id_reg and id_com values don't match with some registered Region and Commune.";
        }
    }
    catch(PDOException $err){
        error_log('Register Customer PDOException - ' . $err->getMessage(), 0);// Sends the error message to the server log file
        http_response_code(500);// Returns HTTP 500 error and stops the execution...
        die('Error: Register process failed. Please report it to Admin.');// ... showing this message
    }
});


/**
 * To search a registered Customer by dni or email
 * -> POST method
 */
$app->post('/customers/search', function(Request $request, Response $response){
    // Puts into variables the data received in request body
    $dni = $request->getParam('dni');
    $email = $request->getParam('email');
    
    try {
        $dbObj = new apiDB();// Creates an instance of DB class
        $dbObj = $dbObj->connectToDB();// Makes the connection through function

        $selectQuery = "SELECT cus.name, cus.last_name, cus.address, reg.description AS region, com.description AS commune
                FROM customers cus
                LEFT JOIN regions reg ON reg.id_reg = cus.id_reg
                LEFT JOIN communes com ON com.id_com = cus.id_com ";

        $result = "";// Declares the var outside the conditionals to can access it later

        if ($dni != "" && $email != ""){
            $selectQuery .= " WHERE cus.dni = :dni AND cus.email = :email AND cus.status = 'A';";// Before execute the query on database, prepare statement by binding parameters (:param)
            $result = $dbObj->prepare($selectQuery);// Prepares the query string to be executed

            // bindParam function validates user input (API request in this case) before sending it to the DB.
            $result->bindParam(':dni', $dni);
            $result->bindParam(':email', $email);
        }
        else if ($dni != "" && $email == ""){
            $selectQuery .= " WHERE cus.dni = :dni AND cus.status = 'A';";// Before execute the query on database, prepare statement by binding parameters (:param)
            $result = $dbObj->prepare($selectQuery);// Prepares the query string to be executed
            $result->bindParam(':dni', $dni);// bindParam function validates user input (API request in this case) before sending it to the DB.
        }
        else if ($email != "" && $dni == ""){
            $selectQuery .= " WHERE cus.email = :email AND cus.status = 'A';";// Before execute the query on database, prepare statement by binding parameters (:param)
            $result = $dbObj->prepare($selectQuery);// Prepares the query string to be executed
            $result->bindParam(':email', $email);// bindParam function validates user input (API request in this case) before sending it to the DB.
        }
        else {
            echo json_encode(array("message" => "Not enough params to make the Customer search. DNI or email required.", "success" => "false"));
        }

        
        $result->execute();// Executes the select query on the database
        if ($result->rowCount() > 0){
            $customer = $result->fetchAll(PDO::FETCH_OBJ);// Fetch fields and values from obtained resulset
            echo json_encode($customer);// Returns the requested data
            echo json_encode(array("success" => "true"));
        }
        else {
            echo json_encode(array("message" => "No customer found.", "success" => "false"));
        }

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


/**
 * To logically remove/delete a Customer from database
 * -> POST method
 */
$app->post('/customers/delete', function(Request $request, Response $response){
    // Puts into variables the data received in request body
    $dni = $request->getParam('dni');
    $email = $request->getParam('email');
    
    try {
        $dbObj = new apiDB();// Creates an instance of DB class
        $dbObj = $dbObj->connectToDB();// Makes the connection through function

        $validationQuery = "SELECT status FROM customers ";
        $selectQuery = "UPDATE customers SET status = 'trash' ";
        $result = "";// Declares the var outside the conditionals to can access it later
        $validationResult = "";// ... (same purpose that line before)

        if ($dni != "" && $email != ""){
            $validationQuery .= " WHERE dni = :dni AND email = :email;";
            $validationResult = $dbObj->prepare($validationQuery);// Prepares the query string to be executed
            $validationResult->bindParam(':dni', $dni);
            $validationResult->bindParam(':email', $email);// bindParam function validates user input (API request in this case) before sending it to the DB.

            $selectQuery .= " WHERE dni = :dni AND email = :email AND (status = 'A' OR status = 'I');";// Before execute the query on database, prepare statement by binding parameters (:param)
            $result = $dbObj->prepare($selectQuery);// Prepares the query string to be executed
            $result->bindParam(':dni', $dni);
            $result->bindParam(':email', $email);// bindParam function validates user input (API request in this case) before sending it to the DB.
        }
        else if ($dni != "" && $email == ""){
            $validationQuery .= " WHERE dni = :dni;";
            $validationResult = $dbObj->prepare($validationQuery);// Prepares the query string to be executed
            $validationResult->bindParam(':dni', $dni);// bindParam function validates user input (API request in this case) before sending it to the DB.

            $selectQuery .= " WHERE dni = :dni AND (status = 'A' OR status = 'I');";// Before execute the query on database, prepare statement by binding parameters (:param)
            $result = $dbObj->prepare($selectQuery);// Prepares the query string to be executed
            $result->bindParam(':dni', $dni);// bindParam function validates user input (API request in this case) before sending it to the DB.
        }
        else if ($email != "" && $dni == ""){
            $validationQuery .= " WHERE email = :email;";
            $validationResult = $dbObj->prepare($validationQuery);// Prepares the query string to be executed
            $validationResult->bindParam(':email', $email);// bindParam function validates user input (API request in this case) before sending it to the DB.

            $selectQuery .= " WHERE email = :email AND (status = 'A' OR status = 'I');";// Before execute the query on database, prepare statement by binding parameters (:param)
            $result = $dbObj->prepare($selectQuery);// Prepares the query string to be executed
            $result->bindParam(':email', $email);// bindParam function validates user input (API request in this case) before sending it to the DB.
        }
        else {
            echo json_encode(array("message" => "Not enough params to operation delete Customer. DNI or email required.", "success" => "false"));
        }


        $validationResult->execute();// Executes the select validation query to check the data
        if ($validationResult->rowCount() > 0){
            $customerData = $validationResult->fetchAll(PDO::FETCH_OBJ);// Fetch fields and values from obtained resulset
            $obj = json_encode($customerData[0]);
            $obtainedData = json_decode($obj);// To access the JSON object values as properties

            /**
             * Validates if the Customer is already trashed
             * If the status of customer found is different to trash, will be deleted
             */
            if ($obtainedData->status != "trash"){
                // Executes the select query on the database
                if ($result->execute())
                    echo json_encode(array("message" => "Customer successfully deleted.", "success" => "true"));
                else 
                    echo json_encode(array("message" => "Delete operation cannot be completed.", "success" => "false"));
            }
            else {
                echo json_encode(array("message" => "Register doesn't exist.", "success" => "false"));
            }
        }
        else {
            echo json_encode(array("message" => "No customer found.", "success" => "false"));
        }

        // Resets the variables and close the connection to database
        $validationResult = null;
        $result = null;
        $dbObj = null;
    }
    catch(PDOException $err){
        error_log('Register Customer PDOException - ' . $err->getMessage(), 0);// Sends the error message to the server log file
        http_response_code(500);// Returns HTTP 500 error and stops the execution...
        die('Error: Register process failed. Please report it to Admin.');// ... showing this message
    }
});
