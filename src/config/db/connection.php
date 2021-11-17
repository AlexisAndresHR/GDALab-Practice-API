<?php

class apiDB {
    
    // Database conn parameters
    private $dbHost = "localhost";
    private $dbUser = "alexandre";
    private $dbPassword = "P@s5w0rd22!";
    private $dbName = "alexis_gda_api";

    /**
     * Function to stablish the connection to MySQL database
     */
    public function connectToDB() {
        try {
            $mysqlConn = "mysql:host=$this->dbHost;dbname=$this->dbName";// Defines the main connection string
            $connection = new PDO($mysqlConn, $this->dbUser, $this->dbPassword);// Creates an instance of PDO passing the DB params
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Attributes setted to capture errors and write it to a log file.
            
            // Validates if the connection has been established or not
            if ($connection)
                return $connection;
            else 
                echo "DB connection error. Something went wrong.";
        }
        catch(PDOException $err){
            error_log('apiDB PDOException - ' . $err->getMessage(), 0);// Sends the error message to the server log file
            http_response_code(500);// Returns HTTP 500 error and stops the execution...
            die('Error: Cannot establish connection with database');// ... showing this message
        }
    }

}
