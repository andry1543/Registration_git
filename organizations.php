<?php
    include "header.php";

      
    $title = "Поиск людей по организации";
    $guestid = '';
    $vip = 0;
    //echo $_GET['guestid'];
    $org_en = $_GET['org'];
    //echo $org_en;
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
                source: 'autocmpl_org.php',
                //source: 'autocomplete.php',
                //minLength: 2,
                select: function (event, ui) {
                    var code = ui.item.value;
                                        if (code != '') {
                        location.href = '/organizations.php?org=' + code;
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        <!--
        /*
        originally written by paul sowden <paul@idontsmoke.co.uk> | http://idontsmoke.co.uk
        modified and localized by alexander shurkayev <alshur@ya.ru> | http://htmlcssjs.ru
        */

        var initial_sort_id = 0;
        var initial_sort_up = 1;

        var img_dir = "/imgs/"; // папка с картинками
        var sort_case_sensitive = false; // вид сортировки (регистрозависимый или нет)

        // ф-ция, определяющая алгоритм сортировки
        function _sort(a, b) {
            var a = a[0];
            var b = b[0];
            var _a = (a + '').replace(/,/, '.');
            var _b = (b + '').replace(/,/, '.');
            //if (parseFloat(_a) && parseFloat(_b)) return sort_numbers(parseFloat(_a), parseFloat(_b));
            //else 
            if (!sort_case_sensitive) return sort_insensitive(a, b);
            else return sort_sensitive(a, b);
        }

        // ф-ция сортировки чисел
        function sort_numbers(a, b) {
            return new Date(b.date) - new Date(a.date);
            //return a - b;
        }

        // ф-ция регистронезависимой сортировки
        function sort_insensitive(a, b) {
            var anew = a.toLowerCase();
            var bnew = b.toLowerCase();
            if (anew < bnew) return -1;
            if (anew > bnew) return 1;
            return 0;
        }

        // ф-ция регистрозависимой сортировки
        function sort_sensitive(a, b) {
            if (a < b) return -1;
            if (a > b) return 1;
            return 0;
        }

        // вспомогательная ф-ция, выдирающая из дочерних узлов весь текст
        function getConcatenedTextContent(node) {
            var _result = "";
            if (node == null) {
                return _result;
            }
            var childrens = node.childNodes;
            var i = 0;
            while (i < childrens.length) {
                var child = childrens.item(i);
                switch (child.nodeType) {
                    case 1: // ELEMENT_NODE
                    case 5: // ENTITY_REFERENCE_NODE
                        _result += getConcatenedTextContent(child);
                        break;
                    case 3: // TEXT_NODE
                    case 2: // ATTRIBUTE_NODE
                    case 4: // CDATA_SECTION_NODE
                        _result += child.nodeValue;
                        break;
                    case 6: // ENTITY_NODE
                    case 7: // PROCESSING_INSTRUCTION_NODE
                    case 8: // COMMENT_NODE
                    case 9: // DOCUMENT_NODE
                    case 10: // DOCUMENT_TYPE_NODE
                    case 11: // DOCUMENT_FRAGMENT_NODE
                    case 12: // NOTATION_NODE
                    // skip
                    break;
                }
                i++;
            }
            return _result;
        }

        // суть скрипта
        function sort(e) {
            var el = window.event ? window.event.srcElement : e.currentTarget;
            while (el.tagName.toLowerCase() != "td") el = el.parentNode;
            var a = new Array();
            var name = el.lastChild.nodeValue;
            var dad = el.parentNode;
            var table = dad.parentNode.parentNode;
            var up = table.up;
            var node, arrow, curcol;
            for (var i = 0; (node = dad.getElementsByTagName("td").item(i)); i++) {
                if (node.lastChild.nodeValue == name){
                    curcol = i;
                    if (node.className == "curcol"){
                        arrow = node.firstChild;
                        table.up = Number(!up);
                    }else{
                        node.className = "curcol";
                        arrow = node.insertBefore(document.createElement("img"),node.firstChild);
                        table.up = 0;
                    }
                    arrow.src = img_dir + table.up + ".gif";
                    arrow.alt = "";
                }else{
                    if (node.className == "curcol"){
                        node.className = "";
                        if (node.firstChild) node.removeChild(node.firstChild);
                    }
                }
            }
            var tbody = table.getElementsByTagName("tbody").item(0);
            for (var i = 0; (node = tbody.getElementsByTagName("tr").item(i)); i++) {
                a[i] = new Array();
                a[i][0] = getConcatenedTextContent(node.getElementsByTagName("td").item(curcol));
                a[i][1] = getConcatenedTextContent(node.getElementsByTagName("td").item(1));
                a[i][2] = getConcatenedTextContent(node.getElementsByTagName("td").item(0));
                a[i][3] = node;
            }
            a.sort(_sort);
            if (table.up) a.reverse();
            for (var i = 0; i < a.length; i++) {
                tbody.appendChild(a[i][3]);
            }
        }

        // ф-ция инициализации всего процесса
        function init(e) {
            if (!document.getElementsByTagName) return;

            for (var j = 0; (thead = document.getElementsByTagName("thead").item(j)); j++) {
                var node;
                for (var i = 0; (node = thead.getElementsByTagName("td").item(i)); i++) {
                    if (node.addEventListener) node.addEventListener("click", sort, false);
                    else if (node.attachEvent) node.attachEvent("onclick", sort);
                    node.title = "Нажмите на заголовок, чтобы отсортировать колонку";
                }
                thead.parentNode.up = 0;
        
                if (typeof(initial_sort_id) != "undefined"){
                    td_for_event = thead.getElementsByTagName("td").item(initial_sort_id);
                    if (document.createEvent){
                        var evt = document.createEvent("MouseEvents");
                        evt.initMouseEvent("click", false, false, window, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, td_for_event);
                        td_for_event.dispatchEvent(evt);
                    } else if (td_for_event.fireEvent) td_for_event.fireEvent("onclick");
                    if (typeof(initial_sort_up) != "undefined" && initial_sort_up){
                        if (td_for_event.dispatchEvent) td_for_event.dispatchEvent(evt);
                        else if (td_for_event.fireEvent) td_for_event.fireEvent("onclick");
                    }
                }
            }
        }

        // запускаем ф-цию init() при возникновении события load
        var root = window.addEventListener || window.attachEvent ? window : document.addEventListener ? document : null;
        if (root){
            if (root.addEventListener) root.addEventListener("load", init, false);
            else if (root.attachEvent) root.attachEvent("onload", init);
        }
        //-->
        </script>

    </head>

    <body>
    <div>
        <h2>Введите организацию: </h2>
        
        <input id="tag" size="40"  type="text"  class = "textbox">
    </div>
    <div align="center">
            <table  id="guests" class="sort">
                        <?php
                            $query = $db->query("SELECT sum(Visited = 1) as Visited, sum(1) as Alll FROM ".$dbtable." WHERE Organization like '".$org_en."'");
                            $row = $query->fetch_assoc();
                            $Visited = $row['Visited'];
                            $All = $row['Alll'];
                        if ($org_en != '')
                        {
                            echo "<caption><h1>".$org_en."<br>Всего:  ".$All.", посетило: ".$Visited."</h1></caption>";
                            echo "<thead>";
                            echo "<tr>";
                            echo "<td>Время регистрации</td>";
                            echo "<td>Гость</td>";
                            echo "<td>Должность</td>";
                            echo "<td>Посещение</td>";
                            echo "</tr>";
                            echo "</thead>";
                            }
                        ?>
                        <tbody>
                        
                            <?php
                                if ($org_en != '')
                                {
                                    include "header.php";
                                    $query = $db->query("SELECT * FROM ".$dbtable." WHERE Organization like '".$org_en."'");
                                    $i = 0;    
                                    while ($row = $query->fetch_assoc()) {
                                        if ($row['Surname_en'] != '' or $row['Name_en'] != '')
                                            echo '<tr><td><h3>'.$row['RegDate'].'</h3></td><td><h3><a href = "../../index.php?guestid='.$row['ID'].'">'.$row['Surname_en'].' '.$row['Name_en'].'</a></h3>
                                                     </td><td><h3>'.$row['Position'].'</h3></td><td><h3>'.$row['Visited'].'</h3></td></tr>';
                                        else
                                            echo '<tr><td><h3>'.$row['RegDate'].'</h3></td><td><h3><a href = "../../index.php?guestid='.$row['ID'].'">'.$row['Surname_ru'].' '.$row['Name_ru'].'</a></h3>
                                                     </td><td><h3>'.$row['Position'].'</h3></td><td><h3>'.$row['Visited'].'</h3></td></tr>';
                                        $i++;
                                    }
                                }
                            ?>
                        </tbody>
                    </table>

    </div>
    <div id="footer">
        <div class="form">
            <br>

            <FORM method= "GET" action="index.php">
                <INPUT type="submit" name = button class ="button" value= "Страница регистрации">
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
