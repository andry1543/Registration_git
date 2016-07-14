<?php
    
    require "header.php";
    require "text_to_image.php";

    $guestid = $_GET['ID'];
   
    $top_settings['ru'] = 126;
    $top_settings['en'] = 50;
    $top_settings['org'] = 70;
    

    //get matched data from our table
    if ($guestid != '') {
        $query = $db->query("SELECT * FROM ".$dbtable." WHERE ID = ".$guestid);
        
        $row = $query->fetch_assoc();
        $string_row['en'] = $row['Surname_en']." ".$row['Name_en'];
        //$string_row['ru'] = $row['Name_ru']." ".$row['Surname_ru'];
        $string_row['org'] = $row['Organization'];
        
    }
    else $string_row['ru'] = "Выберите пользователя!";
        
    
    

    # Получаем объект класса наложения текста
    $image = new ImageCreate($string_row, $top_settings);
          
    # Генерируем изображение и получаем путь
    $path = $image->create();
    //echo $path;
            


?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <link rel="stylesheet" href="style.css" type="text/css">

        <title>Печать бейджа</title>

        <script>
	        window.onload = function () {
	            var printBut = document.getElementById('printBut');
	            printBut.onclick = function () {
	                var win = window.open();
	                win.document.write('<img src="Template/temp.png"/>');
                    win.print();
	                win.close();
                    //window.location = "http://localhost:13138/index.php";
	            }
                var returnBut = document.getElementById('returnBut');
                returnBut.onclick = function () {
                    window.location = "index.php";
	            }
    
	        }
	    </script>


    </head>
    <body>
        <div id="img">
            <img src="Template/temp.png" alt="Бейдж" />
        </div>
        <h1>Имя: <br><?php echo $row['Surname_en']." ".$row['Name_en'];?></h1>
        <br>
        <h1>Организация: <br><?php echo $string_row['org'];?></h1>
        <br><br>
        <div id="footer">
        <button id="printBut" class="button">Напечатать</button>
        <br><br>
        <button id="returnBut" class="button">Вернуться</button>
        </div>
   </body>

</html>
	
