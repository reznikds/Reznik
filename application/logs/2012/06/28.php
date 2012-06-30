<?php defined('SYSPATH') or die('No direct script access.'); ?>

2012-06-28 16:37:59 --- ERROR: ErrorException [ 1 ]: Call to undefined method Database_MySQL_Result::getMaterialsByTeacher() ~ APPPATH\classes\controller\public.php [ 40 ]
2012-06-28 16:37:59 --- STRACE: ErrorException [ 1 ]: Call to undefined method Database_MySQL_Result::getMaterialsByTeacher() ~ APPPATH\classes\controller\public.php [ 40 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2012-06-28 16:50:20 --- ERROR: Kohana_Exception [ 0 ]: A valid hash key must be set in your auth config. ~ MODPATH\auth\classes\kohana\auth.php [ 153 ]
2012-06-28 16:50:20 --- STRACE: Kohana_Exception [ 0 ]: A valid hash key must be set in your auth config. ~ MODPATH\auth\classes\kohana\auth.php [ 153 ]
--
#0 D:\Server\domains\kohana\modules\orm\classes\kohana\auth\orm.php(82): Kohana_Auth->hash('admin')
#1 D:\Server\domains\kohana\modules\auth\classes\kohana\auth.php(90): Kohana_Auth_ORM->_login('admin@thesuppor...', 'admin', false)
#2 D:\Server\domains\kohana\application\classes\controller\auth.php(55): Kohana_Auth->login('admin@thesuppor...', 'admin')
#3 [internal function]: Controller_Auth->action_login()
#4 D:\Server\domains\kohana\system\classes\kohana\request\client\internal.php(118): ReflectionMethod->invoke(Object(Controller_Auth))
#5 D:\Server\domains\kohana\system\classes\kohana\request\client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#6 D:\Server\domains\kohana\system\classes\kohana\request.php(1138): Kohana_Request_Client->execute(Object(Request))
#7 D:\Server\domains\kohana\index.php(109): Kohana_Request->execute()
#8 {main}
2012-06-28 16:56:37 --- ERROR: ErrorException [ 1 ]: Call to undefined method Database_MySQL_Result::getMaterialsByTeacher() ~ APPPATH\classes\controller\public.php [ 40 ]
2012-06-28 16:56:37 --- STRACE: ErrorException [ 1 ]: Call to undefined method Database_MySQL_Result::getMaterialsByTeacher() ~ APPPATH\classes\controller\public.php [ 40 ]
--
#0 [internal function]: Kohana_Core::shutdown_handler()
#1 {main}
2012-06-28 17:47:00 --- ERROR: HTTP_Exception_404 [ 404 ]: The requested URL loginform/1 was not found on this server. ~ SYSPATH\classes\kohana\request\client\internal.php [ 113 ]
2012-06-28 17:47:00 --- STRACE: HTTP_Exception_404 [ 404 ]: The requested URL loginform/1 was not found on this server. ~ SYSPATH\classes\kohana\request\client\internal.php [ 113 ]
--
#0 D:\Server\domains\kohana\system\classes\kohana\request\client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#1 D:\Server\domains\kohana\system\classes\kohana\request.php(1138): Kohana_Request_Client->execute(Object(Request))
#2 D:\Server\domains\kohana\application\views\account\index.php(2): Kohana_Request->execute()
#3 D:\Server\domains\kohana\system\classes\kohana\view.php(61): include('D:\Server\domai...')
#4 D:\Server\domains\kohana\system\classes\kohana\view.php(343): Kohana_View::capture('D:\Server\domai...', Array)
#5 D:\Server\domains\kohana\system\classes\kohana\view.php(228): Kohana_View->render()
#6 D:\Server\domains\kohana\application\views\layouts\main.php(16): Kohana_View->__toString()
#7 D:\Server\domains\kohana\system\classes\kohana\view.php(61): include('D:\Server\domai...')
#8 D:\Server\domains\kohana\system\classes\kohana\view.php(343): Kohana_View::capture('D:\Server\domai...', Array)
#9 D:\Server\domains\kohana\application\classes\basecontroller.php(28): Kohana_View->render()
#10 [internal function]: Basecontroller->after()
#11 D:\Server\domains\kohana\system\classes\kohana\request\client\internal.php(121): ReflectionMethod->invoke(Object(Controller_Account))
#12 D:\Server\domains\kohana\system\classes\kohana\request\client.php(64): Kohana_Request_Client_Internal->execute_request(Object(Request))
#13 D:\Server\domains\kohana\system\classes\kohana\request.php(1138): Kohana_Request_Client->execute(Object(Request))
#14 D:\Server\domains\kohana\index.php(109): Kohana_Request->execute()
#15 {main}