<?php 

<?php
/***
 * @author: Buty(孟婆)
 * 
 */

class picWords {
    private $width;
    private $height;
    private $zimo_file = 'piczimo.php'; //需要生成的字模文件
    private $zimo_info = '';
    private $continued; //高峰流量持续的时间
    private $warnning_data; //流量报警峰值
    private $hourstep; //一小时在时间轴上的长度
    function __construct() {
        if(!extension_loaded('gd')) { //需要gd扩展的支持
            exit('GD extension is supported,please load GD extension and go on.');
        }
        if(file_exists('config.php')) {
            require 'config.php';
            $this->continued = $continued;
            $this->warnning_data = $warnning_data;
            $this->hourstep = $hourstep;
        }
        
    }

/*****************************************自行创建字模图片与字模数组文件********************************/    
    function readPicDot($picfile) {
        $res = imagecreatefrompng($picfile);
        $size = getimagesize($picfile);
        $this->width = $size[0];
        $this->height = $size[1];

        $data = array();
        
        for($j = 0; $j < $this->height; ++$j) {
            /*
             * 取需要的数据段 
             * 262,273 day.png
             */
            
            if($j > 262 && $j < 273) {   
                for($i=0; $i < $this->width; ++$i) {
                    if($i > 420) {
                        $rgb = imagecolorat($res,$i,$j);
                        $rgbarray = imagecolorsforindex($res, $rgb);
                        if($rgbarray['red'] < 145 && $rgbarray['green'] < 145 & $rgbarray['blue'] < 145) {
                            $data[$i][$j]=1;
                        } else {
                            $data[$i][$j]=0;
                        }
                    }
                }
                
            }
        }
        
        return $data;
    }
    function readMaxOutDot($picfile) {
        $res = imagecreatefrompng($picfile);
        $size = getimagesize($picfile);
        $this->width = $size[0];
        $this->height = $size[1];

        $data = array();
        
        for($j = 0; $j < $this->height; ++$j) {
            /*
             * 取需要的数据段 
             * 262,273 day.png
             */
            
            if($j > 262 && $j < 273) {   
                for($i=0; $i < $this->width; ++$i) {
                    if($i < 180 && $i > 90) {
                        $rgb = imagecolorat($res,$i,$j);
                        $rgbarray = imagecolorsforindex($res, $rgb);
                        if($rgbarray['red'] < 145 && $rgbarray['green'] < 145 & $rgbarray['blue'] < 145) {
                            $data[$i][$j]=1;
                        } else {
                            $data[$i][$j]=0;
                        }
                    }
                }
                
            }
        }
        
        return $data;
    }    
    
    
    function readHourDot($picfile) {
        $res = imagecreatefrompng($picfile);
        $size = getimagesize($picfile);
        $this->width = $size[0];
        $this->height = $size[1];

        $data = array();
        
        for($j = 0; $j < $this->height; ++$j) {
            /*
             * 取需要的数据段 
             * popup_module_view_hour.png
             * 25, 245 # 35, 480
             * 
             */
            
            if($j > 30 && $j < 242) {   
                for($i=0; $i < $this->width; ++$i) {
                    if($i >66 && $i < 486) {
                        $rgb = imagecolorat($res,$i,$j);
                        $rgbarray = imagecolorsforindex($res, $rgb);
                        if(($rgbarray['red'] < 50 && $rgbarray['green'] > 180 && $rgbarray['blue'] < 50) || ($rgbarray['red'] < 145 && $rgbarray['green'] < 145 & $rgbarray['blue'] < 145)) {
                            $data[$i][$j]=1;
                        } else {
                            $data[$i][$j]=0;
                        }
                    }
                }
                
            }
        }
        
        return $data;
    }
    
    function cutData($data) {
        $zimo = $tmp = array();
        $num = $len = 0;
        $count_start = false;
        foreach($data as $k => $v) {
            if(in_array(1, $v)) {
                
                $zimo[$num][] = $v;
                $count_start = true;
                $len++;
                if($len >= 6) {  //解决字体连在一起
                    $len = 0;
                    $num++; //字数加1
                }
                
            } else {
                $len = 0;
                $count_start = false;
            }
            if(!$count_start) {
                $num++;
            }
        }
        //var_dump(array_keys($zimo));
        
        //对于字母T的修复
        $res = $this->createZimoArray($zimo);
        //$this->debugPic($zimo[35]);
        //die();
        //打印完整字模点阵
        /*
        foreach($zimo as $k => $v) {

            if($k == 35) {
                echo '<hr />';
                $this->debugPic($v);
            }
        }
        */
        
        //var_dump($res);
        end($res);//移动数据指针
        $last_two_data = prev($res);
        
        if(!strcmp($last_two_data, '0100000000')) { //说明切割T时存在问题
            $last_three_data = prev($res);
            $last_three_data_real = $last_three_data . $last_two_data; //补充到T字母中
            foreach($res as $k => $v) {
                if(!strcmp($v, $last_three_data)) {
                    $res[$k] = $last_three_data_real;
                }
            }
            
        }
        return $res;
    }
    
    //构造字模数组
    function createZimoArray($data) {
        $str = array();
        foreach($data as $k => $v) {
            $str[$k] = '';
            foreach($v as $ks => $vs) {
                $str[$k] .= implode('', $vs);
            }
        }
        return $str;
    }
    
    //将数据字模读入文件中
    function storeZimo($data) {
        $str = $this->createZimoArray($data);
        $picwords = APP_CACHE_PATH.'picwords.php';
        swritefile($picwords, "<?php\r\nreturn ".var_export($str, true)."\r\n?>");

    }
    //匹配字模数据
    function matchZimo($str_arr) {
        $need_str = '';
        $picwords = $this->zimo_file;
        if(empty($this->zimo_info)) {
            $this->zimo_info = include_once($picwords);
        }
        foreach($str_arr as $k => $v) {
            foreach($this->zimo_info as $ks => $vs) {
                similar_text($v, $vs, $tmp);
                if($tmp > 99)
                    $need_str .= $ks;
            }
        }
        return $need_str;
    }
    
    function debugPic($data) {
            echo '<div style="width:5850px">';
            foreach($data as $k => $v) {
                echo '<div style="float:left">';
                foreach($v as $ks => $vs) {
                    echo $vs."<br />";
                }
                echo '</div>';
            }
            echo '</div>';
    }
    function handleHourData($data) {
        $data_info = array();
        foreach($data as $k => $v) {
            $get = true;
            $data_info[$k] = 0;
            foreach($v as $ks => $vs) {
                if($vs == 1 && $get) {
                    $get = false;
                }
                if(!$get) {
                    $data_info[$k]++;
                    
                } else {
                    unset($data[$k][$ks]);
                }
            }
        }
        return $data_info;
    }

/************************************取数据的方法*******************************/
    //总流量
    function getPicWords($file) {
        $need_str = '';
        //读取图片点阵信息
        $data = $this->readPicDot($file);
        
        //处理切割到的数据，并写入数组
        $data = $this->cutData($data);

        //匹配字模数据
        $need_str = $this->matchZimo($data);
        return $need_str;
    }
    //得到最大流量速度
    function getMaxSpeed($file) {
        $need_str = '';
        
        //读取图片点阵信息
        $data = $this->readMaxOutDot($file);
        //处理切割到的数据，并写入数组
        $data = $this->cutData($data);
        //匹配字模数据
        $need_str = $this->matchZimo($data);
        return $need_str;
    }
    //分析小时图中的流量峰值
    function parseFlow($file, $info) {
        //得到最大流量速度
        $maxflow = floatval($this->getMaxSpeed($file));

        //读取图片点阵信息
        $data = $this->readHourDot($file);
        //数据抽象形变
        $data_info = $this->handleHourData($data);
        
        $max = max($data_info);
        
        $radio = (float) ($maxflow / $max); //流量计算比列
        
        $timeradio = ceil($this->hourstep / 60);//每分钟格数
        
        $continued_time = $this->continued * $timeradio;//持续时间用点阵长度量化
        
        $count = 0;
        foreach($data_info as $v) {
            if($v * $radio > $this->warnning_data) {
                $count++;
                if($count >= $continued_time) {
                    echo "ServerID: $info[sid] IP: $info[ip] flow over: ".$this->warnning_data.'<br />'; //发邮件
                    break;
                }
            } else {
                $count = 0;
            }
        }

    }
    
}
?>
 ?>