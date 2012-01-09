<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Core_Backend extends Controller_Layout 
{
	
	public $layout = "admin/layout";
	
	/**
	 * If FALSE, the controller is actually public, all actions.
	 * If string, it has the role name to match exactly.
	 * If array, we pass it to the Auth, all roles must be present.
	 * If associative array we have action level control.
	 *
     * @var mixed 
     */
    public $auth_required = 'admin';    
    
	/**
	 * @var	Model_Auth_User
	 */
	protected $user = NULL;
	
	/**
	 * @var string Type of controller.
	 */
	protected $_type = "backend";
	
	/**
	 * @var Auth	Auth instance.
	 */
	protected $_auth;
	
	/**
	 * 
	 */
	public function before()
    {
    	$this->_auth = Auth::instance();
		
        // Check user auth and role
        $this->_check_auth();
		
		$this->user = Auth::instance()->get_user();
		View::set_global('user',$this->user);
		
        parent::before();
    }
	
	/**
	* Controller-level access denial message for
	* internal requests
	*/
	public function action_denied()
	{
		$this->request->redirect('admin/noaccess');
	}
	
	/**
	 * Required to find the partials under admin section.
	 */
	public function get_partial_path($partial)
	{
		return 'admin/'.$partial;
	}
	
	/**
	 * 
	 */
	protected function _check_auth()
	{
		//If we are not logged in, try to autolog.
		if(!Auth::instance()->logged_in())
		{	
		 	try
		 	{
		 		Auth::instance()->auto_login();
		 	}
			catch(Exception $e){}
        }
		 
		 //auth is required AND user role given in auth_required is NOT logged in
        // OR secure_actions is set AND the user role given in secure_actions is NOT logged in
        if($this->_fails_role())
		{
			$this->secured_access();
		} 
		else if ($this->_fails_action())
        {
        	 // user is logged in but not on the secure_actions list
            if (Auth::instance()->logged_in())
            {
				$this->secured_action();
            }
            else
            {
            	$this->secured_access();
            }
        } 
        else
		{
			//fails nothing, we move on!
		}
		
		
	}
	
	public function secured_action()
	{
		Notice::error("You don't have permissions to do this.", $this->request->controller());		
		$this->request->redirect('admin/noaccess');
	}
	
	public function secured_access()
	{
		Notice::error("You have to be logged in", $this->request->controller());
		$this->request->redirect('admin/login');
	}
	
	/**
	 * 
	 */
	private function _fails_role()
    {
        $role = $this->auth_required;
		
		//We made the whole thing is public.
		if($role === FALSE)
		{
			return FALSE;
		}
		
		//We have one role i.e. admin, check for this one.
		if(is_string($role))
		{
			return Auth::instance()->logged_in(array($role)) === FALSE;
		}
		
		//We have a set of roles i.e editor,moderator,publisher. Check them all.
		if(is_array($role) && ! Arr::is_assoc($role))
		{
			return Auth::instance()->logged_in($role) === FALSE;
		}
		
		return FALSE;
    }
    
    private function _fails_action()
    {    	
    	$actions = $this->auth_required;
		
		//We most likely have a string
		if(!is_array($actions))
		{
			return FALSE;
		}
		
		$have_secured_actions = Arr::is_assoc($actions);
		
		//we don't have secured actions.
		if( ! $have_secured_actions)
		{
			return FALSE;
		}
		
		$action  = $this->request->action();
		$action_is_restricted = array_key_exists($action, $actions);
		
		//current action is not restricted
		if( ! $action_is_restricted)
		{
			return FALSE;
		}
		
		//We get all posible roles for action, and check that.
		$action_role  = $actions[$action];
		if(is_string($action_role)) $action_role = array($action_role);
		
		$role_matched = Auth::instance()->logged_in($action_role);
		
        return $role_matched === FALSE; 
    }
}	