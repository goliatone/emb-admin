<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Dashboard extends Controller_Backend
{
	/**
	 * 
	 */
	public $auth_required = array('index' => 'admin');
	
	public function action_index()
	{
		$this->action_scope = 'login';
		$paths = $this->request->directory().DIRECTORY_SEPARATOR.$this->controller.DIRECTORY_SEPARATOR.$this->action_scope.DIRECTORY_SEPARATOR.$this->action;
		//$this->action_scope = '';
		
		$ar = array($this->request->directory(),$this->controller,$this->action_scope,$this->action);
		
		$content = $paths.'<br/>';
		$content .= implode(DIRECTORY_SEPARATOR,array_filter($ar)).'<br/>';
		$content .= Auth::instance()->hash('admin').'<br/>';
		$this->template->content->set("content", $content);
		
		$this->template->sidebar->set("content","<p>What the fuck is this crap?</p>");
	}
	
}	