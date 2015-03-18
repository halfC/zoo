<?php
/**
 * 批量删除指定表达式匹配出来的keys
 * redis 版本大于 2.8.0
 * 本地环境版本不够，没测试
 * shell redis-cli KEYS "topic*" | xargs redis-cli DEL
 * 
 * 
 */
$redis = new Redis();
$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);


$it = NULL;
while($arr_matches = $redis->zscan('zset', $it, '*user*')) {
    foreach($arr_matches as $str_mem => $f_score) {
        echo "Key: $str_mem, Score: $f_score\n";
    }
}