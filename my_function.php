<?php

function get_td_array($table) {  
        $table = preg_replace("'<table[^>]*?>'si","",$table);  
        $table = preg_replace("'<tr[^>]*?>'si","",$table);  
        $table = preg_replace("'<td[^>]*?>'si","",$table);  
        $table = str_replace("</tr>","{tr}",$table);  
        $table = str_replace("</td>","{td}",$table);  
        //ȥ�� HTML ���   
        $table = preg_replace("'<[\/\!]*?[^<>]*?>'si","",$table);  
        //ȥ���հ��ַ�    
        $table = preg_replace("'([\r\n])[\s]+'","",$table);  
        $table = str_replace(" ","",$table);  
        $table = str_replace(" ","",$table);  

        $table = explode('{tr}', $table);  
        array_pop($table);  
        foreach ($table as $key=>$tr) {  
                $td = explode('{td}', $tr);  
                array_pop($td);  
            $td_array[] = $td;  
        }  
        return $td_array;  
} 

?>