<?php

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Статистика</title>

        <link rel="stylesheet" href="style.css" type="text/css">

        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript">
            // Load the Visualization API and the piechart package.
            google.load('visualization', '1.1', { 'packages': ['corechart'] });

            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawChart);

            function drawChart() {
                var jsonData = $.ajax({
                    url: "getData.php",
                    dataType: "json",
                    async: false
                }).responseText;

                var options = {
                    legend: 'none',
                    hAxis: { minValue: 0, maxValue: 9,  title: 'Минут назад'},
                    vAxis: { format: 'decimal', title: 'Регистраций' }, 
                    curveType: 'function',
                    height: 500,
                    pointSize: 20

                };

                // Create our data table out of JSON data loaded from server.
                var data = new google.visualization.DataTable(jsonData);

                


                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            }

        </script>


    </head>
    <body>
        <div class="form">
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
        <div id="chart_div"></div>  
    </body>
</html>
