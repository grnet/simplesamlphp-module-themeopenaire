<?php 
    if (isset($_POST['mail'])) {
        $mail = $_POST['mail'];
    };
    if (isset($_POST['sn'])) {
        $sn = $_POST['sn'];
    };
    if (isset($_POST['givenName'])) {
        $givenName = $_POST['givenName'];
    };
    if (isset($_POST['eduPersonScopedAffiliation'])) {
        $eduPersonScopedAffiliation = $_POST['eduPersonScopedAffiliation'];
    };
    echo 'success';
?>
