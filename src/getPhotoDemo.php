<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fanz
 * Date: 12-10-31
 * Time: 上午9:32
 * To change this template use File | Settings | File Templates.
 */
set_time_limit(0);//设置PHP超时时间

$imagesURLArray = array(
    'http://172.22.21.1/photo/10061000122.jpg',
    'http://172.22.21.1/photo/1006100095.jpg'
);
$imagesURLArray = array_unique($imagesURLArray );

if(!is_dir("photo")){
    mkdir("photo");
}

foreach($imagesURLArray as $imagesURL) {
    echo $imagesURL;
    echo "<br/>";

    if(@fopen($imagesURL, 'b') && !@fopen("photo/" .basename($imagesURL), 'b')){
        file_put_contents( "photo/" .basename($imagesURL), file_get_contents($imagesURL));
    }

}