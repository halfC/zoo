<?php

format,int [timestamp]) 
//把表示日期和时间的字符串转化成时间戳。可以接受大多数典型数据格式的字符串。例如，YYYY-MM-DD 
和MM/DD/YYYY 

int strtotime(string time,int now) 
//取得目前时间（UNIX时间戳格式）百万分之一秒值。返回字符串 

string microtime(void) 
//以数组类型返回当前时间 

array gettimeofday(void) 

//设置随机数种 
void srand(int seed) 

//返回一个随机数，若没有指定随机数的最大及最小范围，本函数会自动从0到rand_max中取一个随机数 。若有指定min及max的参数，则从指定参数中取一个数字 

int rand([int min],[int max]) 

//返回随机数可允许的最大值 
int getrandmax(void) 

//把一个数字的二进制表示形式转化成十六进制 
string bin2hex(string tring) 

//除去字符串结尾处的空格 

string rtrim(string string) 
//rtrim的别名 

string chop(string string) 
//从字符串的两头除去空格 

string trim(string string) 
//从字符串的开头处除去空格 

string ltrim(string string) 
//用字符串 separator 来分割字符串 string 并在数组中返回 string 字符串的各组成部分 

array explode(string separator,string string) 
//通过在数组 pieces 各元素之间加上 glue 字符串，把各元素组合起来并返回一个字符串 

string implode(string glue,array pieces) 
//使一个字符串变成大写形式 

string strtoupper(string string) 
//使一个字符串变成小写形式 

string strtolower(string string) 
//将字符串 str 和 from 中相同的字符串一一转换成 to 中的字符串并返回 

string strtr(string str,string from,string to) 
//找到一个字符串中的字符在另一个字符串中的首次出现（不分大小写）的位置 

int strpos(string haystack,string needle,int [offset]) 
//找到字符串中的字符在另一个字符串中最后一次出现的位置，值得注意的是 needle 只能是一个字符， 
中文字符不适合 

int strrpos(string haystack,char needle) 
//将字符串变成小段供其他函数使用使用。例如，base64_encode。内定是参数chunklen(76个字符)每隔 76个字符插入end(" ")。返回新字符串而不改动原字符串。 

string chunck_split(string string,int[chunklen],string[end]) 
//将字符串 string 的第 start 位起的字符串取出 length 个字符。若 start 为负数，则从字符串尾部 算起。若可省略的参数 length 存在，但为负数，则表示取到倒数第 length 个字符 

string substr(string string,int start,int[length]) 
//返回的字符串中下列字符的前面都加上了反斜（\）：.\+*?[^](＄) 

string quotemeta(string str) 
//返回字符串的ASCII（美国国家标准交换码）序数值。本函数和chr()函数相反 

int ord(string string) 
//把ASCII码转化成一个字符 

string chr(int ascii) 
//使一个字符串的首字符大写 

string ucfirst(string str) 
//使一个字串中每个单词的首字符大写 

string ucwords(string str) 
//比较两个字符的相同程度，返回两个字符串中相同的字符序列（chars）的个数，通过使用第3个参数中 
给出的引用变量，把相似字符百分比传递给第3个参数 

int similar_text(string first,string second,double[percent] 
//把一个字符中的单引号、双引号和反斜杠字符都用反斜杠进行转义 

string addslashes(string str) 
//从字符串中除去反斜杠 

string stripslashes(string str) 
//以 pattern 的规则来分析比对字符串 string ，比对结果返回的值放在数组参数 regs 之中，regs[0]  内容就是原字符串 string、regs[1]为第一个合乎规则的字符串、regs[2]就是第二个合乎规则的字符串 ，依此类推。若省略参数 regs，则只是单纯地比对，找到则返回值为 true 

int ereg(string pattern,string string,array)[regs]) 
//和ereg()类似，不同之处在于ereg()区分大小写，本函数与大小写无关 

int eregi(string pattern,string string,array[regs]) 
//本函数以 pattern 的规则来分析比对字符串 string，欲取而代之的字符为参数 replacement。返回值 
为字符串类型 

string ereg_replace(string pattern,string replacement,string string) 
//构造一个不区分大小写的替换正则表达式 

string eregi_replace(string pattern,string replacement,string string) 
//切开后的返回值为数组变量。参数 pattern 为指定的规则字符串、参数 string 则为待处理的字符串 、参数 limit 可省略，表示欲处理的最多合乎值。值得注意的是本函数的 pattern 参数区分大小写 

array split(string pattern,string string,int[limit]) 
//本函数可将字符串之字符逐字返回大小写。在 PHP 使用上，本函数没有什么作用，但可能可以提供外 
部程序或数据库处理。 

string sql_regcase(string string) 
//将数据以 byte-stream 方式存放。变量 value 为混合型，可以包括整数、双精度浮点数字串、数组以 
及对象的属性（对象的方法不保存） 

string serialize(mixed value) 
//可取出系统以 byte-stream 方式存放的数据 

mixed unserialize(string str) 

?>