<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hett
 * Date: 05.09.12
 * Time: 12:21
 */

class Html extends CHtml {

    public static function staticImage($src, $alt = '', $htmlOptions=array())
    {
        return parent::image(self::staticUrl($src), $alt, $htmlOptions);
    }

    public static function staticUrl($path)
    {
        return Yii::app()->assetManager->staticUrl . '/' . $path;
    }

    public static function button($label='button', $htmlOptions = array())
    {
        $addClass = 'btn';
        $htmlOptions['class'] = isset($htmlOptions['class'])? $htmlOptions['class'] . ' ' . $addClass : $addClass;
        return parent::button($label, $htmlOptions);
    }

    public static function submitButton($label='button', $htmlOptions = array())
    {
        $htmlOptions['type'] = 'submit';
        $htmlOptions['focus'] = true;
        return self::button($label, $htmlOptions);
    }

    public static function popupButton($label, $url, $htmlOptions = array(), $native = true)
    {
        $htmlOptions['onclick'] = "
            $.ajax({
                url: '$url',
                success:function(data){
                    $.fancybox(data);
                }
            })
        ";

        if($native) {
            return parent::button($label, $htmlOptions);
        } else {
            return self::button($label, $htmlOptions);
        }
    }

    public static function nameToShort($string, $max = 50)
    {
        if(mb_strlen($string) > $max) {
            $half = (int)($max / 2);
            $string = mb_substr($string, 0, $half, Yii::app()->charset) . '...' . mb_substr($string, -$half+3, $half-3, Yii::app()->charset);
        }
        return $string;
    }
}