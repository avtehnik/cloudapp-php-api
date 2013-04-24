<?php

require_once './cloudapp.php';
require_once './config.php';
$app = new Cloudapp(USERNAME, PASSWORD);

echo'<pre>';

//print_r($app->uploadFile(pathinfo(__FILE__, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . 'testuploadfile1.jpg'));
print_r($app->getAccountStats());
print_r($app->getViewAccountDetails());


$items = $app->getListItems();
foreach($items as $item){
//    $item = $app->getViewItem($item->url);
//    print_r($item);
};

//getViewItem
echo'</pre>';