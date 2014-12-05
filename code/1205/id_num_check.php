<?php
/**
 * 身份证号码检测
 * 
 * 
 * 
 */

$idCard  = '12345678901234567';//身份证号码前17位
$wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
$ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
$sigma = null;
for ($i = 0; $i < 17; $i++) {
	$sigma += ((int) $idCard{$i}) * $wi[$i];
}
echo "身份证号码：".$idCard.$ai[($sigma % 11)];
/***
 *  附带参考的js代码
 *  iW = new Array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2,1); 
 *   iSum = 0; 
 *   for( i=0;i<17;i++){
 *   	iC = v_card.charAt(i) ; 
 *   	iVal = parseInt(iC); 
 *   	iSum += iVal * iW[i]; 
 * 	 } 
 
    
 *		iJYM = iSum % 11; 
 *		var sJYM = ’’; 
 *		if(iJYM == 0) sJYM = "1"; 
 *		else if(iJYM == 1) sJYM = "0"; 
 *		else if(iJYM == 2) sJYM = "x"; 
 *		else if(iJYM == 3) sJYM = "9"; 
 *		else if(iJYM == 4) sJYM = "8"; 
 *		else if(iJYM == 5) sJYM = "7"; 
 *		else if(iJYM == 6) sJYM = "6"; 
 *		else if(iJYM == 7) sJYM = "5"; 
 *		else if(iJYM == 8) sJYM = "4"; 
 *		else if(iJYM == 9) sJYM = "3"; 
 *		else if(iJYM == 10) sJYM = "2"; 
 *		var cCheck = v_card.charAt(17).toLowerCase(); 
 *		if( cCheck != sJYM ){ 
 *		    return false; //对不上就是假号码 
 *		}
 *
 */
?>