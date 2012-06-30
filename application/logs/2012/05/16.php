<?php defined('SYSPATH') or die('No direct script access.'); ?>

2012-05-16 03:36:37 --- ERROR: Exception [ 0 ]: Node id=4 does not exist. ~ APPPATH/classes/nstree.php [ 52 ]
2012-05-16 03:36:37 --- STRACE: Exception [ 0 ]: Node id=4 does not exist. ~ APPPATH/classes/nstree.php [ 52 ]
--
#0 /home/reznik/html/THESUPPORT.INFO/application/classes/nstree.php(152): NSTree->getNode('4')
#1 /home/reznik/html/THESUPPORT.INFO/application/classes/model/category.php(27): NSTree->getPath('4')
#2 /home/reznik/html/THESUPPORT.INFO/application/classes/controller/public.php(27): Model_Category->getPath('4')
#3 [internal function]: Controller_Public->action_shownode()
#4 /home/reznik/html/THESUPPORT.INFO/system/classes/kohana/request/client/internal.php(118): ReflectionMethod->invoke(Object(Controller_Public))
#5 /home/reznik/html/THESUPPORT.INFO/system/classes/kohana/request/client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 /home/reznik/html/THESUPPORT.INFO/system/classes/kohana/request.php(1138): Kohana_Request_Client->execute(Object(Request))
#7 /home/reznik/html/THESUPPORT.INFO/index.php(109): Kohana_Request->execute()
#8 {main}