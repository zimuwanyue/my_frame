<?php

function dump($data=''){
    var_dump($data);exit;
}

/**
 * 设置缓存
 * @param type $key
 * @param type $value
 * @param type $expire
 */
function setVar($key,$value,$expire='3600'){
    $mem = new MmCache(MEM_HOST,MEM_PORT);
    $mem->set($key,$value,$expire);
}

/**
 * 获取缓存参数
 * @param type $key
 * @return type
 */
function getVar($key){
    $mem = new MmCache(MEM_HOST,MEM_PORT);
    return $mem->get($key);
}

function delVar($key){
    $mem = new MmCache(MEM_HOST,MEM_PORT);
    return $mem->remove($key);
}

function R(){
   $redis = new YzmRedis(REDIS_HOST,REDIS_PORT);
   return $redis;
}
