<?php
// Brings the Request and Response classes to use its in the code
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';// To use the project dependencies
require '../src/config/db/connection.php';


$app = new \Slim\App;// Creates new Slim App instance

// ...
require "../src/middleware.php";

// Loads customers route
require "../src/routes/customers.php";


$app->run();// Executes the API and its functions
