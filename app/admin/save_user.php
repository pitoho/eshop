<?php
$user = new User($_POST);
$result = Eshop::saveUser($user);
if($result){
    echo SAVE_USER_OK;
    header('Refresh: 3, url=create_user');
}else{
    echo SAVE_USER_ERROR;
    exit;
}