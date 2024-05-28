<?php
session_start();
$response = array('isAdmin' => false);

if (isset($_SESSION['user_logged_in']) && $_SESSION['is_admin']) {
    $response['isAdmin'] = true;
}

echo json_encode($response);
?>