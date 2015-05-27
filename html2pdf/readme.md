>>Use
```
		import('Org.Util.html2fpdfzh.html2fpdf');
        // exit();
        $pdf = new \HTML2FPDF();
        $pdf->AddGBFont('GB','仿宋_GB2312');
        $pdf->AddPage();
        $fp = fopen("sample.html","r");
        $strContent = fread($fp, filesize("sample.html"));
        fclose($fp);
        $pdf->WriteHTML(iconv("UTF-8","GB2312",$h));
        $top = 130;
        foreach($image_arr as $img){
            //echo $img['img'];
            //echo "<br>";
            if(file_exists('.'.$img['img'])){
                $pdf->Image($img['fileurl'],40,$top,140,0,'jpg');
                //echo 'aaa';
            }else{
                $pdf->Cell($top+20,10,$img['fileurl'].'<br />');
            }
            $top += 200;
        }
        ob_clean();
        $pdf->Output("tmp.pdf",true);

