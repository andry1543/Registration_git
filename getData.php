<?php
require "header.php";//Подключение к БД

//error_reporting(E_ERROR | E_WARNING | E_PARSE);

$string = array();

$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    array('label' => 'Время', 'type' => 'string'),
    array('label' => 'Количество регистраций', 'type' => 'number')

);
for($i = 60; $i >= 0; $i = $i - 5)
{
    $query = $db->query("SELECT count(ID) as ID, ".$i." as minute FROM ".$dbtable." WHERE TIME_TO_SEC(RegDate) > TIME_TO_SEC(CURRENT_TIMESTAMP()) - 60 * ".($i + 5)." and 
                            TIME_TO_SEC(RegDate) <= TIME_TO_SEC(CURRENT_TIMESTAMP()) - 60 * ".$i);
    $row = $query->fetch_assoc();
    $temp = array();
    // the following line will be used to slice the Pie chart
    $temp[] = array('v' => $row['minute']); 

    // Values of each slice
    $temp[] = array('v' => (int) $row['ID']); 
    $rows[] = array('c' => $temp);
   
}

$table['rows'] = $rows;
echo json_encode($table);

?>
