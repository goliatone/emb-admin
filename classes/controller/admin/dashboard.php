<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Backend
{
	public $layout = "admin/dashboard";
	protected $_partials  = array('content'=> '', 'footer'=> '', 'header'=> '');
	
	/**
	 * 
	 */
	public $auth_required = array('index' => 'admin');
	
	public function action_index()
	{
		
		//Dashboard content is just gatehered from all over our application, givin each module the
		//change to add to it. 
		 
		$content = '';
		$event = new Event("content.dashboard");
		$event->bind('dashboard_content', $content);
		
		Dispatcher::instance()->dispatch_event($event);
		
		$this->template->content->set("content", $content);
		
		$sidebar_content = "<p>What the fuck is this crap? Are we ready or not</p>";
		$this->template->sidebar->bind("content",$sidebar_content);
	}
	
}	