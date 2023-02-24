<?php
$conn = mysqli_connect('localhost','root','heoquay113','shop_db') or die('connection failed');
$image_foldr = 'uploaded_img/';
$default_img = '201818.png';

$FEW_STOCK_LIMIT = 8;

function error_trigger($msg) {
    die($msg);
}

function session_check_if_not_logout($session_array, $attribute) {
    if( strcmp(gettype($attribute), 'string') != 0 ) {
        error_trigger('session_handler() error!!');
    }

    if( !isset($session_array[$attribute]) || is_null($session_array[$attribute]) ) {
        header('location:login.php');
    }
    return $session_array[$attribute];
}

function get_session_attribute($session_array, $attribute) {
    if( strcmp(gettype($attribute), 'string') != 0 ) {
        error_trigger('session_handler() error!!');
    }

    if( !isset($session_array[$attribute]) || is_null($session_array[$attribute]) ) {
        return NULL;
    }
    return $session_array[$attribute];
}
?>