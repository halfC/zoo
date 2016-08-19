<?php 

    include('./qrlib.php'); 

// text output   
    $codeContents = '12sfdasfda345'; 
     
    // generating 
    $text = QRcode::text($codeContents); 
    $raw = join("\n", $text); 
     /*
     
     BLACK = '\033[40m  \033[0m'
            WHITE = '\033[47m  \033[0m'
      */
    $raw = strtr($raw, array( 
        '0' => "\033[40m  \033[0m", 
        '1' => "\033[47m  \033[0m" 
    ));
    // displaying 
    echo $raw; 