<?php
    include "header.php";
    include "libmail.php";

    $send_mail = FALSE;

    $sec= 0;
      
    $title = "Регистрация гостей";
    $guestid = '';
    $vip = 0;
    //echo $_GET['guestid'];
    $guestid = $_GET['guestid'];
    //echo $guestid;
    if ($guestid != '') {
        $query = $db->query("SELECT * FROM ".$dbtable." WHERE ID = ".$guestid);
        $row = $query->fetch_assoc();
        $visit = 0;
        if ($row['Name_en'] == '' and $row['Surname_en'] == '') $title = $row['Surname_ru']." ".$row['Name_ru'];
        else $title = $row['Surname_en']." ".$row['Name_en'];
        //$guest = $title;
        if ($row['Visited'] == 1) {
            $guest= $title;//.' - Прошел регистрацию';
            $reg = 'Регистрация пройдена в '.$row['RegTime'];
            $visit = 1;
        }
        else 
        {   
            $guest = $title;//.' - Регистрация не пройдена';
            $reg = 'Регистрация не пройдена!';
        }
        if ($row['VIP'] == 'VIP') 
        {
            //$guest= $guest.', VIP!';
            $vip = 1;
        }
		if ($row['Stat'] == 'Confirmed') 
        {
            $stat= 1;
        }
        //else {$guest= $guest.'!';}
        $org_en = $row["Organization"];
        $pos_en = $row["Position"];
    }

   $query = $db->query("SELECT 
                            sum(Visited = 1 and Organization = 'RDIF') as Visited_rdif
                            , sum(Visited = 1 and (Organization != 'RDIF' or Organization is null)) as Visited_guests
                            , sum(Stat = 'Confirmed' and Organization = 'RDIF') as Confirmed_rdif
                            , sum(Stat = 'Confirmed' and (Organization != 'RDIF' or Organization is null)) as Confirmed_guests
                            , count(*) as Alll 
                            FROM ".$dbtable);
            $row = $query->fetch_assoc();
            $All = $row['Alll'];
            $Visited_rdif = $row['Visited_rdif'];
            $Confirmed_rdif = $row['Confirmed_rdif'];
            $Visited_guests = $row['Visited_guests'];
            $Confirmed_guests = $row['Confirmed_guests'];
    
     if ( $_GET['button'] == "Внести в базу нового гостя") {
         echo '<script language="javascript">';
         $sec = (int)microtime(true);
         echo "location.href = '/registration.php?guestid=".$sec."';";
         echo '</script>';
         }

                
    if ( $_GET['button'] == "Зарегистрировать гостя") {
        if ($guestid != '') {
            $query = $db->query("SELECT Visited, VIP FROM ".$dbtable." WHERE ID = ".$guestid);
            $row = $query->fetch_assoc();
            //$guest= $title.' - Прошел регистрацию';
            if ($row['Visited'] == 0) {
                $db->query("UPDATE ".$dbtable." SET Visited = 1, RegDate = CURRENT_TIMESTAMP(), RegTime = CURTIME() WHERE ID = ".$guestid);
                if ($row['VIP'] == 'VIP') {
                    //$guest= $guest.', VIP!';
                    $vip = 1;
                    if ($send_mail == TRUE) sendmail("Прошел регистрацию"); 
                    echo '<script language="javascript">';
                    echo "location.href = '/index.php';";
					echo '</script>';
                    
                }
                else {
                    //$guest= $guest.'!';
                    echo '<script language="javascript">';
                    echo "location.href = '/index.php';";
                    echo '</script>';
                    }
            }
            else {
                echo '<script language="javascript">';
                echo 'var r = confirm("Гость уже зарегистирован. Завести нового гостя в базу?");';
                echo 'if (r == true) {';
                $sec = (int)microtime(true);
                echo "location.href = '/registration.php?guestid=".$sec."';";
                echo '} ';
                echo '</script>';
            }

        }
        else {
            echo '<script language="javascript">';
            echo 'var r = confirm("Завести нового гостя в базу?");';
            echo 'if (r == true) {';
            $sec = (int)microtime(true);
            echo "location.href = '/registration.php?guestid=".$sec."';";
            echo '} ';
            echo '</script>';
        }
    }

    if ( $_GET['button'] == "Убрать регистрацию") {
        if ($guestid != '') {
            $query = $db->query("SELECT Visited, VIP FROM ".$dbtable." WHERE ID = ".$guestid);
            $row = $query->fetch_assoc();
            $guest= $title;
            if ($row['Visited'] == 1) {
                $db->query("UPDATE ".$dbtable." SET Visited = 0, RegTime = NULL WHERE ID = ".$guestid);
                if ($row['VIP'] == 'VIP') {
                    //$guest= $guest.', VIP!';
                    $vip = 1;
                    if ($send_mail == TRUE) sendmail("Ошибочная регистрация"); 
                    echo '<script language="javascript">';
                    echo 'alert("Гость разрегистрирован!\n       VIP!")';
                    echo '</script>';
                    $reg = 'Регистрация не пройдена';
                }
                else {
                    $guest= $guest.'!';
                    echo '<script language="javascript">';
                    echo 'alert("Гость разрегистрирован!")';
                    echo '</script>';
                    $reg = 'Регистрация не пройдена';
                    }
            }
            else {
                echo '<script language="javascript">';
                echo 'alert("Гость не проходил регистрацию!")';
                echo '</script>';
            }

        }
    }


    function sendmail($subject)
    {
        $m= new Mail('windows-1251');  // можно сразу указать кодировку, можно ничего не указывать ($m= new Mail;)
        $m->From( "your@mail.ru" ); // от кого Можно использовать имя, отделяется точкой с запятой
        //$m->ReplyTo( 'Сергей Вадимыч;replay@bk.ru' ); // куда ответить, тоже можно указать имя
        $m->To( "some@mail.ru" );   // кому, в этом поле так же разрешено указывать имя
        $m->Subject($subject);
        $m->Body("VIP".$subject);
        //$m->Cc( "kopiya@asd.ru");  // кому отправить копию письма
        //$m->Bcc( "skritaya_kopiya@asd.ru"); // кому отправить скрытую копию
        //$m->Priority(4) ;	// установка приоритета
        //$m->Attach( "/toto.gif", "", "image/gif" ) ;	// прикрепленный файл типа image/gif. типа файла указывать не обязательно
        $m->smtp_on("ssl://smtp.mail.ru","your_name","Password", 465, 10); // используя эу команду отправка пойдет через smtp
        //$m->log_on(true); // включаем лог, чтобы посмотреть служебную информацию
        $m->Send();	// отправка
        //echo "Письмо отправлено, вот исходный текст письма:<br><pre>", $m->Get(), "</pre>";
        return TRUE;
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" type="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/redmond/jquery-ui.css">
	


    <title><?php echo $title ?></title>


    <script src="//code.jquery.com/jquery-1.11.3.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    


    <script>
        $(function () {
            $("#tag").autocomplete({
                source: 'autocomplete.php',
                //minLength: 2,
                select: function (event, ui) {
                    var code = ui.item.id;
                    if (code != '') {
                        location.href = '/index.php?guestid=' + code;
                    }
                }
            });
        });
    </script>



    </head>

    <body>
    <div>
        <h2>Введите ID или имя гостя: </h2>
        
        <input id="tag" size="40"  type="text"  class = "textbox">
    </div>
    <div class= "text">
        <p>
            <?php  if ($guestid != '') {
                        echo "<i>Гость</i>:<br><b>".$guest."</b>";
						if ($stat == 1)echo "<br>ID: <b>".$guestid."</b>";
                        if ($vip == 1) echo "<br><b>VIP!</b>";
                        echo "<br><br><i>Должность</i>:<br><b>".$pos_en."</b><br><br>";
                        echo "<i>Организация</i>:<br><b>".$org_en."</b><br><br>";
                        echo "<i><b>".$reg."</b></i>";
                   }
            ?>
        </p>

    </div>
    <div id="footer">
        <div class="form">
            <br>

            <FORM method= "GET" action="index.php">
                <INPUT type="hidden" name="guestid" value="<?php echo $guestid;?>">
                <INPUT type="submit" name = button class ="button" value= "Зарегистрировать гостя">
            </FORM>

        </div>
       <br>
       <!-- <div  class="form">
            <FORM method= "GET" action="badge.php">
                <INPUT type="hidden" name="ID" value="<?php //echo $guestid;?>">
                <INPUT type="hidden" name = button class ="button" value= "Распечатать бейдж">
           </FORM> 
        </div>
        <br>
        <div class="form">
           <FORM method= "GET" action="index.php">
                <INPUT type="hidden" name="guestid" value="<?php //echo $guestid;?>">
                <INPUT type="hidden" name = button class ="button" value= "Убрать регистрацию">
           </FORM>
        </div>-->
        <div class="form">
           <FORM method= "GET" action="index.php">
               <INPUT type="submit" name = button class ="button" value= "Внести в базу нового гостя">
           </FORM>
        </div>
        <br>
        <div class="form">
           <FORM method= "GET" action="guests.php">
               <INPUT type="submit" name = button class ="button" value= "Список гостей">
           </FORM>
        </div>
        <br>
        <div class="form">
           <FORM method= "GET" action="stat.php">
               <INPUT type="submit" name = button class ="button" value= "Статистика">
           </FORM>
        </div>
     <h1>Зарегистрировано:&nbsp<?php echo $Visited_guests + $Visited_rdif;?>, из них сотрудников РФПИ:&nbsp<?php echo $Visited_rdif;?>
         <br>Ожидается гостей:&nbsp<?php echo $Confirmed_guests;?> Всего:&nbsp<?php echo $All;?></h1>

    </div>
    </body>
</html>
