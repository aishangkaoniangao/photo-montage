```
composer require aishangkaoniangao/photo-montage dev-master
```
测试代码
```
require_once './vendor/autoload.php';
use aishangkaoniangao\photomontage\images;

$path = "图片目录";
$img = new images();
img->run($path);
exit('执行完毕!');
```
