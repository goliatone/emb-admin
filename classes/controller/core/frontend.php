<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Core_Frontend extends Controller_Layout 
{
	/**
	 * @var string Type of controller.
	 */
	protected $_type = "frontend";
	
	/**
	 * @var	Model_User
	 */
	protected $user = NULL;
	
	public function before()
    {
        $this->user = Auth::instance()->get_user();
		View::set_global('user', $this->user);
				
        parent::before();
    }
}	