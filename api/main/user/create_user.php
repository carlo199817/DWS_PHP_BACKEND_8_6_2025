<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST"); 
    $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    
    echo json_encode($input['email']);