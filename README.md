Cloudapp php api
================

Overview
--------
This is simple php api written for file storage service http://cloudapp.com

Basic usage
--------

    $app = new Cloudapp('username', 'password'); // make     new object with username and password

    $app->getViewAccountDetails(); // get account details
    $app->getAccountStats(); // get account stats 
    $app->uploadFile('testuploadfile1.jpg') // upload file  

For more details about functions please follow https://github.com/cloudapp/api/blob/master/README.md

ps. not all functions  are implemented in this api
