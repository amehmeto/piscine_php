<?php

function createPrivateDirectory(){
    $path = "../private";

    if (!file_exists($path))
        if (!mkdir($path, 0700))
            echo "Directory creation failed\n";
}

function prepareNewCredentials($credentials){
    unset($credentials['submit']);
    $credentials['passwd'] = hash('whirlpool', $credentials['passwd']);
    return $credentials;
}

function buildCredentialsList($credentials){
    $credentials = prepareNewCredentials($credentials);
    $path = "../private/passwd";
    if (!file_exists($path)) {
        $credentials_list = array();
    }
    else {
        $serialized_list = file_get_contents($path);
        $credentials_list = unserialize($serialized_list);
    }
    array_push($credentials_list, $credentials);
    $serialized_list = serialize($credentials_list);
    return $serialized_list;
}
function storeCredentials($new_credentials){
    createPrivateDirectory();
    $credentials_list = buildCredentialsList($new_credentials);
    file_put_contents("../private/passwd", $credentials_list);
    echo "OK\n";
}

$error_message = "ERROR\n";

if (!isset($_POST['login']) OR !isset($_POST['passwd']))
    echo $error_message;
if ($_POST['login'] === '' OR $_POST['passwd'] === '')
    echo $error_message;
else
    storeCredentials($_POST);
