<?php
    require "header.php";//Подключение к БД
    
    
    
    header('Content-Type: text/html; charset=UTF-8');

    mb_internal_encoding('UTF-8');
    mb_http_output('UTF-8');
    mb_http_input('UTF-8');
    mb_regex_encoding('UTF-8'); 
    
    //get search term
    $searchTerm = $_GET['term'];
    $searchTerm = mb_strtolower($searchTerm);
    

    $a_json_invalid = array(array("id" => "#", "value" => $searchTerm, "label" => "Only letters and digits are permitted..."));
    $json_invalid = json_encode($a_json_invalid);
 
    /* SECURITY HOLE *************************************************************** */
    /* allow space, any unicode letter and digit, underscore and dash                */
    if (preg_match("/[^\040\pL\pN_-]/u", $searchTerm)) {
    print $json_invalid;
    exit;
    
    }
    $data_row[] = array();
    $data[] = array();
    $data_fin[] = array();
    $tr_empt[] = array();
    $tr = array ("А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E","Ё"=>"YO","Ж"=>"ZH","З"=>"Z","И"=>"I","Й"=>"I","К"=>"K","Л"=>"L",
                 "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH","Ц"=>"C","Ч"=>"CH",
                 "Ш"=>"SH","Щ"=>"ZCH","Ъ"=>"","Ы"=>"Y","Ь"=>"","Э"=>"EH","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b","в"=>"v","г"=>"g",
                 "д"=>"d","е"=>"e","ё"=>"yo","ж"=>"zh","з"=>"z","и"=>"i","й"=>"i","к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p",
                 "р"=>"r","с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"ch","ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"zch","ъ"=>"y","ы"=>"y",
                 "ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya"//,"."=>"-"," "=>"-","?"=>"-","/"=>"-","\\"=>"-","*"=>"-",":"=>"-","*"=>"-",
                 //">"=>"-","|"=>"-","'"=>""
                 ); //это массив для транслитерации*/
    
    //заготовка для проверки раскладки при вводе
    /*$rus_str = array("й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
                     "ф","ы","в","а","п","р","о","л","д","ж","э",
                     "я","ч","с","м","и","т","ь","б","ю");
    $eng_str= array("q","w","e","r","t","y","u","i","o","p","[","]",
                    "a","s","d","f","g","h","j","k","l",";","'",
                    "z","x","c","v","b","n","m",",",".");
    
    $ru_incor = preg_replace($eng_str, $rus_str, $searchTerm);
    $en_incor = preg_replace($rus_str, $eng_str, $searchTerm);
    $trsearchTerm_incor  = strtr($ru_incor ,$tr);*/

    $searchTerm_tr  = strtr($searchTerm, $tr);
    $searchTerm_tr = mb_strtolower($searchTerm_tr);



    //get matched data from our table
    $query = $db->query("SELECT * FROM ".$dbtable." WHERE 
                        ID LIKE '%".$searchTerm."%' ORDER BY Name_en, Surname_en ASC");
    $i = 0;    
    while ($row = $query->fetch_assoc()) {
        array_push($data, create_data($row));
        $i++;
    }
    if ($i == 0) {
        $query = $db->query("SELECT * FROM ".$dbtable);
        while ($row = $query->fetch_assoc()) 
        {
			similar_text(mb_strtolower($row["Surname_ru"]), $searchTerm, $percent);
            if ($percent > 60) 
            {
                if (!search($data, 'id', $row["ID"]))
                { 
                    array_push($data, create_data($row, $percent));
                }
            }
			similar_text(mb_strtolower($row["Name_ru"]), $searchTerm, $percent);
            if ($percent > 90) 
            {
                if (!search($data, 'id', $row["ID"]))
                { 
                    array_push($data, create_data($row, $percent));
                }
            }
            similar_text(mb_strtolower($row["Surname_en"]), $searchTerm_tr, $percent);
            if ($percent > 50) 
            {
                if (!search($data, 'id', $row["ID"]))
                { 
                    array_push($data, create_data($row, $percent));
                }
            }
            similar_text(mb_strtolower($row["Name_en"]), $searchTerm_tr, $percent);
            if ($percent > 80) 
            {
                if (!search($data, 'id', $row["ID"]))
                { 
                    array_push($data, create_data($row, $percent));
                }
            }
            similar_text(mb_strtolower($row["Surname_en"].' '.$row["Name_en"]), $searchTerm_tr, $percent);
            if ($percent >80)
            {
                if (!search($data, 'id', $row["ID"]))
                { 
                    array_push($data, create_data($row, $percent));
                }
            }
        }
    }

    $data_sort = array_sort($data, "percent", SORT_DESC);
    $data_fin = array_slice($data_sort,0,10);


    //return json data
    echo json_encode($data_fin);
    flush();

function create_data($row, $pct = 100)
{
    
    //$data_row["percent"] = 0;
    if (($row['Name_ru'] != '' or $row['Surname_ru'] != '') and ($row['Name_en'] != '' or $row['Surname_en'] != '')){
        $string_row = $row['Surname_en']." ".$row['Name_en']." (".$row['Surname_ru']." ".$row['Name_ru'].")";
        $value= $row['Surname_en']." ".$row['Name_en'];
    }
    else {
        if ($row['Name_ru'] != '' or $row['Surname_ru'] != '') $string_row = $row['Surname_ru']." ".$row['Name_ru'];
        if ($row['Name_en'] != '' or $row['Surname_en'] != '') $string_row = $row['Surname_en']." ".$row['Name_en']; 
        $value = $string_row;
        $string_row = $string_row;    
    }
    if ($row['Visited'] == 1) {$string_row= $string_row.' - Регистрация пройдена';}
    $data_row["label"] = $string_row;
    $data_row["value"] = $value;
    $data_row["id"] = $row['ID'];
    $data_row["percent"] = $pct;
     
    return $data_row;  
}

function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function search($array, $key, $value)
{
    $results = array();

    if (is_array($array)) {
        if (isset($array[$key]) && $array[$key] == $value) {
            $results[] = $array;
        }

        foreach ($array as $subarray) {
            $results = array_merge($results, search($subarray, $key, $value));
        }
    }

    return $results;
}
?>