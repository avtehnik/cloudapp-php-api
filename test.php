<?php

require_once './cloudapp.php';
require_once './config.php';




$app = new Cloudapp(USERNAME , PASSWORD);

echo'<pre>';


//print_r($app->getAccountStats());
//print_r($app->getListItems());

print_r($app->uploadFile('testuploadfile1.jpg'));

echo'</pre>';