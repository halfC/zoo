<?php

format,int [timestamp]) 
//�ѱ�ʾ���ں�ʱ����ַ���ת����ʱ��������Խ��ܴ�����������ݸ�ʽ���ַ��������磬YYYY-MM-DD 
��MM/DD/YYYY 

int strtotime(string time,int now) 
//ȡ��Ŀǰʱ�䣨UNIXʱ�����ʽ�������֮һ��ֵ�������ַ��� 

string microtime(void) 
//���������ͷ��ص�ǰʱ�� 

array gettimeofday(void) 

//����������� 
void srand(int seed) 

//����һ�����������û��ָ��������������С��Χ�����������Զ���0��rand_max��ȡһ������� ������ָ��min��max�Ĳ��������ָ��������ȡһ������ 

int rand([int min],[int max]) 

//�������������������ֵ 
int getrandmax(void) 

//��һ�����ֵĶ����Ʊ�ʾ��ʽת����ʮ������ 
string bin2hex(string tring) 

//��ȥ�ַ�����β���Ŀո� 

string rtrim(string string) 
//rtrim�ı��� 

string chop(string string) 
//���ַ�������ͷ��ȥ�ո� 

string trim(string string) 
//���ַ����Ŀ�ͷ����ȥ�ո� 

string ltrim(string string) 
//���ַ��� separator ���ָ��ַ��� string ���������з��� string �ַ����ĸ���ɲ��� 

array explode(string separator,string string) 
//ͨ�������� pieces ��Ԫ��֮����� glue �ַ������Ѹ�Ԫ���������������һ���ַ��� 

string implode(string glue,array pieces) 
//ʹһ���ַ�����ɴ�д��ʽ 

string strtoupper(string string) 
//ʹһ���ַ������Сд��ʽ 

string strtolower(string string) 
//���ַ��� str �� from ����ͬ���ַ���һһת���� to �е��ַ��������� 

string strtr(string str,string from,string to) 
//�ҵ�һ���ַ����е��ַ�����һ���ַ����е��״γ��֣����ִ�Сд����λ�� 

int strpos(string haystack,string needle,int [offset]) 
//�ҵ��ַ����е��ַ�����һ���ַ��������һ�γ��ֵ�λ�ã�ֵ��ע����� needle ֻ����һ���ַ��� 
�����ַ����ʺ� 

int strrpos(string haystack,char needle) 
//���ַ������С�ι���������ʹ��ʹ�á����磬base64_encode���ڶ��ǲ���chunklen(76���ַ�)ÿ�� 76���ַ�����end(" ")���������ַ��������Ķ�ԭ�ַ����� 

string chunck_split(string string,int[chunklen],string[end]) 
//���ַ��� string �ĵ� start λ����ַ���ȡ�� length ���ַ����� start Ϊ����������ַ���β�� ��������ʡ�ԵĲ��� length ���ڣ���Ϊ���������ʾȡ�������� length ���ַ� 

string substr(string string,int start,int[length]) 
//���ص��ַ����������ַ���ǰ�涼�����˷�б��\����.\+*?[^](��) 

string quotemeta(string str) 
//�����ַ�����ASCII���������ұ�׼�����룩����ֵ����������chr()�����෴ 

int ord(string string) 
//��ASCII��ת����һ���ַ� 

string chr(int ascii) 
//ʹһ���ַ��������ַ���д 

string ucfirst(string str) 
//ʹһ���ִ���ÿ�����ʵ����ַ���д 

string ucwords(string str) 
//�Ƚ������ַ�����ͬ�̶ȣ����������ַ�������ͬ���ַ����У�chars���ĸ�����ͨ��ʹ�õ�3�������� 
���������ñ������������ַ��ٷֱȴ��ݸ���3������ 

int similar_text(string first,string second,double[percent] 
//��һ���ַ��еĵ����š�˫���źͷ�б���ַ����÷�б�ܽ���ת�� 

string addslashes(string str) 
//���ַ����г�ȥ��б�� 

string stripslashes(string str) 
//�� pattern �Ĺ����������ȶ��ַ��� string ���ȶԽ�����ص�ֵ����������� regs ֮�У�regs[0]  ���ݾ���ԭ�ַ��� string��regs[1]Ϊ��һ���Ϻ�������ַ�����regs[2]���ǵڶ����Ϻ�������ַ��� ���������ơ���ʡ�Բ��� regs����ֻ�ǵ����رȶԣ��ҵ��򷵻�ֵΪ true 

int ereg(string pattern,string string,array)[regs]) 
//��ereg()���ƣ���֮ͬ������ereg()���ִ�Сд�����������Сд�޹� 

int eregi(string pattern,string string,array[regs]) 
//�������� pattern �Ĺ����������ȶ��ַ��� string����ȡ����֮���ַ�Ϊ���� replacement������ֵ 
Ϊ�ַ������� 

string ereg_replace(string pattern,string replacement,string string) 
//����һ�������ִ�Сд���滻������ʽ 

string eregi_replace(string pattern,string replacement,string string) 
//�п���ķ���ֵΪ������������� pattern Ϊָ���Ĺ����ַ��������� string ��Ϊ��������ַ��� ������ limit ��ʡ�ԣ���ʾ����������Ϻ�ֵ��ֵ��ע����Ǳ������� pattern �������ִ�Сд 

array split(string pattern,string string,int[limit]) 
//�������ɽ��ַ���֮�ַ����ַ��ش�Сд���� PHP ʹ���ϣ�������û��ʲô���ã������ܿ����ṩ�� 
����������ݿ⴦�� 

string sql_regcase(string string) 
//�������� byte-stream ��ʽ��š����� value Ϊ����ͣ����԰���������˫���ȸ������ִ��������� 
����������ԣ�����ķ��������棩 

string serialize(mixed value) 
//��ȡ��ϵͳ�� byte-stream ��ʽ��ŵ����� 

mixed unserialize(string str) 

?>