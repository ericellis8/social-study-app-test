<?php
/**
* @package Mia-Chat
* @copyright Brilaps, LLC (http://brilaps.com)
* @license The MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

include('includes/utility_functions.php');

/* Only spend time processing the image if captcha is enabled globally
and GD is supported on the server */
$cpatchaCheck = doCaptcha();
if ($cpatchaCheck!==false) {
    include('includes/php5captcha/Captcha.php');        
    $options['sessionName'] = 'miahash';
    $options['fontPath'] = 'includes/php5captcha';
    $options['fontFile'] = 'anonymous.gdf';
    $options['imageWidth'] = 150;
    $options['imageHeight'] = 50;
    $options['allowedChars'] = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $options['charWidth'] = 40;
    $options['blurRadius'] = 3.0;
    $options['secretKey'] = $cpatchaCheck;

    $captcha = new Captcha($options);
    $captcha->getCaptcha();
}
?>
