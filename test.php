<?php
/**
 *
 * @author wangzhuang
 * @date 2020-03-18
 *
 */

require_once 'images.php';

$images = new images();
$rpath = array(
    "/Users/wangzhuang/work/www/image_test",
);
foreach($rpath as $value){
    $images->run($value);
}
exit('执行结束!');
