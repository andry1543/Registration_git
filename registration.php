<?php
require "header.php";

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_regex_encoding('UTF-8'); 

$VIP = '';
$surname_ru = "";
$name_ru = "";
$org_ru = "";
$surname_en = "";
$name_en = "";
$org_en = "";
$reg = 0;

$maint = $_GET['maintaince'];
$guestid = $_GET['guestid'];
$button = $_GET['button'];

$tr = array ("А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ё"=>"YO","Ж"=>"ZH","З"=>"Z","И"=>"I","Й"=>"I","К"=>"K","Л"=>"L",
                 "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH","Ц"=>"C","Ч"=>"CH",
                 "Ш"=>"SH","Щ"=>"ZCH","Ъ"=>"","Ы"=>"Y","Ь"=>"","Э"=>"EH","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g",
                 "д"=>"d","е"=>"e","ё"=>"yo","ж"=>"zh","з"=>"z","и"=>"i","й"=>"i","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p",
                 "р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"ch","ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"zch","ъ"=>"y","ы"=>"y",
                 "ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya"//,"."=>"-"," "=>"-","?"=>"-","/"=>"-","\\"=>"-","*"=>"-",":"=>"-","*"=>"-",
                 //">"=>"-","|"=>"-","'"=>""
                 ); //это массив для транслитерации*/



if ($maint == 1 && $button == "Внести в базу")
{
    
    if ($_GET['surname_en'] != '') $surname_ru = $_GET['surname_en'];
	$surname_ru = mb_strtoupper($surname_ru);
	$surname_en = strtr($surname_ru, $tr);
    if ($surname_en == $surname_ru) $surname_ru = "";

    if ($_GET['name_en'] != '') $name_ru = $_GET['name_en'];
    $name_en = strtr($name_ru, $tr);
    if ($name_en == $name_ru) $name_ru = "";
    
    if ($_GET['org_en'] != '') $org_ru = $_GET['org_en'];
    //$org_en = strtr($org_ru, $tr);
    //if ($org_en == $org_ru) $org_ru = "";

    if ($_GET['pos_en'] != '') $pos_ru = $_GET['pos_en'];
    //$pos_en = strtr($pos_ru, $tr);
    //if ($pos_en == $pos_ru) $pos_ru = "";
    
    if ($_GET['VIP'] == 'on') $VIP = 'VIP';
    
    if ($_GET['reg'] == 'on') $reg = 1;
    
	
    $db->query("INSERT ".$dbtable." SET Visited = '".$reg."', Surname_en = '".$surname_en."', Surname_ru = '".$surname_ru."', Name_ru = '".$name_ru.
                "',Name_en = '".$name_en."', VIP = '".$VIP."', ID = ".$guestid.", Organization = '".$org_ru."', Position = '".$pos_ru."';");
    //echo "INSERT list_of_badges SET Visited = 0, Surname_en = '".$surname_en."', Name_en = '".$name_en."', VIP =".$VIP.", ID = ".$guestid.";";
    echo '<script language="javascript">';
    echo "location.href = '/index.php?guestid=".$guestid."';";
    echo '</script>';
}
if ($maint == 1 && $button == "Отмена")
{
    echo '<script language="javascript">';
    echo "location.href = '/index.php';";
    echo '</script>'; 
}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="style.css" type="text/css">
        <meta charset="utf-8" />
        <title>Регистрация гостя</title>
    </head>
    <body>
       <FORM method= "GET" action="registration.php">
           <h1>
               <INPUT type="hidden" name="guestid" value="<?php echo $guestid;?>">
               <INPUT type="hidden" name="maintaince" value="1">
               <label for="sur">Фамилия гостя: </label>
               <INPUT id ="sur" type="text" name = surname_en class="textbox">
               <label for="nam">Имя гостя: </label>
               <INPUT id ="nam" type="text" name = name_en class="textbox">
               <label for="org">Организация: </label>
               <INPUT id ="org" type="text" name = org_en class = "textbox">
               <label for="pos">Должность: </label>
               <INPUT id ="pos" type="text" name = pos_en class = "textbox">
               <label for="Vip">VIP: </label>
               <INPUT id ="Vip" type="checkbox" name = VIP>
               <br><br>
               <label for="Vip">Зарегистрировать? </label>
               <INPUT id ="Vip" type="checkbox" name = reg>
               <br><br>
               <INPUT type="submit" name = button value= "Внести в базу" class="button">
               <br><br>
               <INPUT type="submit" name = button value= "Отмена" class="button">
           </h1>
       </FORM>    
    </body>
</html>
