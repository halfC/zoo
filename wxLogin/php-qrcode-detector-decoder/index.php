<?php

include_once('./lib/QrReader.php');






    $qrcode = new QrReader('https://login.weixin.qq.com/qrcode/YZ01T7dtjw==?t=webwx');
    //https://login.weixin.qq.com/l/YZ01T7dtjw==

    print $text = $qrcode->text();
    print "\n";
