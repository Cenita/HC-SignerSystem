<?php
    define('APP_ROOT_PATH', __DIR__); //获得当前PHP文件所在目录的绝对路径，由于该PHP文件在根目录下，所以获得是根目录的绝对路径。例如：D:\software\xampp\htdocs\demo1
    define('HTTP_SERVER', '/hctime'); //定义URL路径，当改变地址的时候，这部分内容要修改。
    define('ADMIN_SERVER', HTTP_SERVER . '/code'); //定义guestbook的URL路径

?>