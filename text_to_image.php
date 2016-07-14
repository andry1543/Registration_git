<?php
class ImageCreate
{
    /**
    * @var $settings - НАСТРОЙКИ
    * src  - Путь к изображению, на которое нанесём текст
    * size - Размер шрифта
    * top_ru - Отступ сверху для русского имени
    * top_en - Отступ сверху для английского имени
    * top_id - Отступ сверху для ID
    * center - центр по горизонтали
    * font - Путь к файлу шрифта
    * save - Путь для сохранения
    */
    private $settings = array(
        "src"  => "Template/template.png",
        "temp" => "Template/temp.png",
        "size_name" => 26,
        "size_org" => 24,
        "center" => 225,
        "width" => 340,
        "font_name" => "Fonts/times.ttf",
        "font_org" => "Fonts/timesbd.ttf",
        "save" => "Template/"

    );
        
    /**
    * 
    * @var Содержит пользовательский текст
    * 
    */
    private $text;
    
    /**
    * 
    * @var Содержит параметры отступа сверху
    * 
    */
    private $top;

    /**
    * 
        * @var Содержит картинку, если есть
        * 
        */
        private $img;
        
        /**
        * 
        * @param пользовательский текст $text
        * 
        */
        public function __construct($text, $top){
            $this->text = $text;
            $this->top = $top;
            $this->img = $image;
        }
        
        /**
        *
        * @return путь к созданному изображению
        * 
        */
        public function create()
        {
            

            # Открываем рисунок в формате PNG
            if ($this->img == '')
                $img = imagecreatefrompng($this->settings["src"]);
            else 
                $img = imagecreatefrompng($this->settings["temp"]);

            # Проверям есть ли файл

            if(!$img){echo "ERR"; exit();}
            
            # Получаем идентификатор цвета
            $color = imagecolorallocate($img, 0, 0, 0);
            
            # Центрируем текст по центру
            $box = imagettfbbox($this->settings["size_name"], 0, $this->settings["font_name"],  $this->text['ru']);
            $left = $this->settings["center"]-round(($box[2]-$box[0])/2);
          
            /* выводим текст на русском на изображение*/ 
            imagettftext(
                $img, 
                $this->settings["size_name"], 
                0, 
                $left - 50, 
                $this->top['ru'], 
                $color, 
                $this->settings["font_name"],
                $this->text['ru']
            );

            $height_tmp = 0;

            $ret = wrap($this->settings["size_name"], 0, $this->settings["font_name"], $this->text['en'], $this->settings["width"]);

            # Центрируем текст по центру
            		// Разбиваем снова на массив строк уже подготовленный текст
		    $arr = explode("\n", $ret);
		    
            //Считаем количество строк
            $i = 0;
            foreach($arr as $str) $i++;

            // Расчетная высота смещения новой строки
            
		
		    //Выводить будем построчно с нужным смещением относительно левой границы
		    foreach($arr as $str)
			    {
				    // Размер строки 
				    $box = imagettfbbox($this->settings["size_name"], 0, $this->settings["font_name"],  $str);
				
				    // Рассчитываем смещение
				    $left = $this->settings["center"]-round(($box[2]-$box[0])/2);

					
				    // Накладываем текст на картинку с учетом смещений
				    imagettftext(
                        $img, 
                        $this->settings["size_name"], 
                        0, 
                        $left - 60, 
                        $this->top['en'] +  $height_tmp, 
                        $color, 
                        $this->settings["font_name"],
                        $str
                    );
				
				    // Смещение высоты для следующей строки
				    $height_tmp = $height_tmp + 40;
			    }

            # Проверям поместится ли название на картинку и подгоняем по размерам

            $ret = wrap($this->settings["size_org"], 0, $this->settings["font_org"], $this->text['org'], $this->settings["width"]);

            # Центрируем текст по центру
            		// Разбиваем снова на массив строк уже подготовленный текст
		    $arr = explode("\n", $ret);
		    
            //Считаем количество строк
            $i = 0;
            foreach($arr as $str) $i++;

            // Расчетная высота смещения новой строки
            if ($i == 1 || $i == 0) $height_tmp = $height_tmp +40;
            elseif ($i == 2) $height_tmp = $height_tmp + 20;
            else $height_tmp = $height_tmp;
		
		    //Выводить будем построчно с нужным смещением относительно левой границы
		    foreach($arr as $str)
			    {
				    // Размер строки 
				    $box = imagettfbbox($this->settings["size_org"], 0, $this->settings["font_org"],  $str);
				
				    // Рассчитываем смещение
				    $left = $this->settings["center"]-round(($box[2]-$box[0])/2);

					
				    // Накладываем текст на картинку с учетом смещений
				    imagettftext(
                        $img, 
                        $this->settings["size_org"], 
                        0, 
                        $left - 60, 
                        $this->top['en'] +  $height_tmp, 
                        $color, 
                        $this->settings["font_org"],
                        $str
                    );
				
				    // Смещение высоты для следующей строки
				    $height_tmp = $height_tmp + 40;
			    }
	    
                                      
            
            # Генерируем путь для сохранения
            $path = $this->settings["save"] . temp . ".png";
            
            # Сохраняем рисунок в формате PNG
            imagepng($img, $path, 0);
            
            # Освобождаем память и закрываем изображение
            imagedestroy($img);
            
            # Возвращаем путь
            return $path;
        }
    }

    function wrap($fontSize, $angle, $fontFace, $string, $width){
    
        $ret = "";
    
        $arr = explode(' ', $string);
    
        foreach ( $arr as $word ){
    
            $teststring = $ret.' '.$word;
            $testbox = imagettfbbox($fontSize, $angle, $fontFace, $teststring);
            if ( $testbox[2] > $width ){
                $ret.=($ret==""?"":"\n").$word;
            } else {
                $ret.=($ret==""?"":' ').$word;
            }
        }
    
        return $ret;
    }

?>
