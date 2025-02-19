<?php

//require_once __DIR__ . '/../../api/security/token.php'; 

function getBearerToken() {
    $bearer_token = '';


    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
        if (strpos($auth_header, 'Bearer ') === 0) {
            $bearer_token = substr($auth_header, 7);
        }
    }


    if (empty($bearer_token) && function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $auth_header = $headers['Authorization'];
            if (strpos($auth_header, 'Bearer ') === 0) {
                $bearer_token = substr($auth_header, 7);
            }
        }
    }


    if (empty($bearer_token)) {
        echo json_encode(["Message" => "Unauthorized, Bearer token is missing."]);
        exit;
    }


    if (strlen($bearer_token) < 20) { 
        echo json_encode(["Message" => "Invalid Token Format, Token too short."]);
        exit;
    }

    $tokens = new MainDb\Configuration\tokens; 

    return json_encode([
        "token"=>$bearer_token,
        "user_id"=>$tokens->decodeToken($bearer_token)['user_id']
    ]);;
}
 

$current_script = basename($_SERVER['PHP_SELF']);
if ($current_script !== 'login.php'&&$current_script !== 'get_client_icon.php') {
    $tokens = new MainDb\Configuration\tokens;  
    $bearer_token = json_decode(getBearerToken(), true)['token'];  

    try {
        $result = $tokens->getValidation($bearer_token);
        if ($result) { 
            return true;
        } else {
            echo json_encode(["Message" => "Unauthorized, Invalid or Expired Token."]);
        }
        exit;
    } catch (Exception $e) { 
        echo json_encode(["Message" => "Error validating token: " . $e->getMessage()]);
        exit;
    }
} 

?>
