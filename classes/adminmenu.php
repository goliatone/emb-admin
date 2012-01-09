<?php defined('SYSPATH') OR die('No direct script access.');

class AdminMenu
{
	
	public static function render_menu( Event $event)
	{
		$menu_bar = $event->main_menu;
		
		$dashboard = new Menu("Dashboard");
		$dashboard->set_link("#dashboard");
		
		$new = new Menu("New Post");
		$new->set_link("#blog/new");
		$new->set_attribute("active","class");
		$dit = new Menu("Edit Post");
		$dit->set_link("#blog/edit");
		
		$dashboard->add_item($new);
		$dashboard->add_item($dit);
		
		
		$menu_bar->add_menu($dashboard);
		
		
	}
	
}