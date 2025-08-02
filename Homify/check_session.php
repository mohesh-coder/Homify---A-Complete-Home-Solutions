<?php
session_start();

$response = array('loggedIn' => false);

if (isset($_SESSION['user_id'])) {
    $response['loggedIn'] = true;
    $response['userName'] = $_SESSION['user_name'];
    $response['isServiceProvider'] = $_SESSION['is_service_provider']; // Adjust based on your session data
}

echo json_encode($response);
?>
