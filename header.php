<?php
    
    //header('Content-Type: text/html; charset=utf-8');
    
    //database configuration
    $dbHost1 = 'localhost';
    $dbUsername1 = 'USername';
    $dbPassword1 = 'PWD';
    $dbName1 = 'dbName';
    $dbtable = 'Tabke';
    
       
    //connect with the databases
    $db = new mysqli($dbHost1,$dbUsername1,$dbPassword1,$dbName1);
    $db->query("SET NAMES 'utf8'");
    //$database2 = new mysqli($dbHost2,$dbUsername2,$dbPassword2,$dbName2);
?>