<?php
include "./vendor/sunra/php-simple-html-dom-parser/Src/Sunra/PhpSimple/HtmlDomParser.php";
use Sunra\PhpSimple\HtmlDomParser;

define("API_SITE","http://yy.tianshuge.cn/home/api/chapter_list/tp/");
define("SITE","http://e12.zhongfaln.com");

$htmls = array(
    "https://yy.tianshuge.cn/home/book/capter/id/41931",
);
foreach ($htmls as $url){
    $file_name = file_get_contents($url);
    preg_match("#doGetChpaterList\(([0-9])+\,#",$file_name,$match);
    $aid = 0;
    foreach ($match as $value){
        if(strstr($value,"doGetChpaterList")){
            $value = trim($value,",");
            $value = explode("(",$value);
            $aid = $value[1];
            break;
        }
    }
    if($aid){
        $url = API_SITE.$aid."-1-1-1000";
        $path_pre = __DIR__."/images/".$aid."/";
        $res = geturl($url);
        if($res['code'] == 1){
            foreach ($res['result']['list'] as $key=>$value){
                $imagelist = explode(",", $value['imagelist']);
                foreach ($imagelist as $k => $v) {
                    $url = SITE.$v;
                    $path = $path_pre.($key+1);
                    if(!is_dir($path)){
                        mkdir($path,777,true);
                    }
                    $filename = $path."/".intval($k+1).".jpg";
                    $res = down_images($url,$filename);

                    if($res){
                        echo $filename."下载成功".PHP_EOL;
                    }else{
                        echo $filename."下载失败".PHP_EOL;
                    }
                }
            }
        }

        echo $aid."文档执行结束".PHP_EOL;
    }else{
        echo $aid."文档为空".PHP_EOL;
    }

    exit;
}
exit;

function down_images($url,$filename) {
    $res = false;

    $header = array("Connection: Keep-Alive", "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8", "Pragma: no-cache", "Accept-Language: zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3", "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:29.0) Gecko/20100101 Firefox/29.0");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_SSLVERSION, 2);//设置SSL协议版本号
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    $content = curl_exec($ch);
    $curlinfo = curl_getinfo($ch);
    curl_close($ch);

    if ($curlinfo['http_code'] == 200) {
        $res = file_put_contents($filename, $content);
    }

    return $res;
}

function geturl($url){
    $header = array("Connection: Keep-Alive", "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8", "Pragma: no-cache", "Accept-Language: zh-Hans-CN,zh-Hans;q=0.8,en-US;q=0.5,en;q=0.3", "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:29.0) Gecko/20100101 Firefox/29.0");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_SSLVERSION, 2);//设置SSL协议版本号
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output,true);
    return $output;
}