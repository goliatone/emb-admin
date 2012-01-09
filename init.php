<?php defined('SYSPATH') OR die('No direct script access.');
#################################
## SET ROUTES FOR LOGIN MODULE
#################################
//Admin Setup
Route::set('emb-admin-setup', 'admin/setup/<password>',array('params' => '.*'))
	->defaults(array(
		'controller' => 'admin',
		'action'     => 'setup',
	));
//
  //Admin Login
Route::set('emb-admin', 'admin/<action>',array('action' => 'login|logout|register|noaccess'))
	->defaults(array(
		'controller' => 'admin',
		'action'     => 'index',
	));	
	
//Admin generic routing we prepend any admin controller with the suffix admin in route.
Route::set('emb-admin-controller', 'admin/(<controller>(/<action>(/<params>)))', array('action' => 'index|show|new|edit|delete','params' => '.*'))
  ->defaults(array(
    'directory'  => 'admin',
    'action'     => 'index',
  ));
  
//Admin generic routing we prepend any admin controller with the suffix admin in route.
Route::set('emb-admin-dashboard', 'admin/dashboard(/<action>(/<params>))', array('action' => 'activity|widgets','params' => '.*'))
  ->defaults(array(
    'directory'  => 'admin',
	'controller' => 'dashboard',    
    'action'     => 'index',
  ));
//Admin generic routing we prepend any admin controller with the suffix admin in route.
Route::set('emb-admin-user', 'admin/user/(<controller>(/<action>(/<params>)))', array('action' => 'create|edit|delete','params' => '.*'))
  ->defaults(array(
    'directory'  => 'admin/user',
	'controller' => 'profile',    
    'action'     => 'index',
  ));
  

#################################
## REGISTER EVENT LISTENER FOR
## MAIN ADMIN MENU.
#################################


$d = Dispatcher::instance();
$d->add_listener('theme.render_menu_main_menu', array('adminmenu', 'render_menu') );