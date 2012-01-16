<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 */
class Controller_Admin extends Controller_Layout 
{
	
	public $layout = "admin/login";
	
	/**
	 * 
	 */
	public $action_scope = "login";
	/**
	 * We add the action scope to the page template path.
	 * So we look into '/'admin/login/logout.php
	 */
	protected $_page_segments = array('directory','controller','action_scope','action');
	
	protected $_redirected_actions = array('index');
	
	/**
	 * Here we redirect, but we still ned a index.php view file
	 * or it will error out.
	 * FIX.
	 */
	public function action_index()
	{
        // Just redirect to login page
        $this->request->redirect('admin/login');
	}
	
	/**
	 * 
	 */
	public function action_login()
	{
		Auth::instance()->logout();
		        
        $this->template->content->bind('errors', $errors);
		
		View::bind_global('user',$user);
		
		//if the user is trying to login
        if ($_POST) {
            // did the user check the Remember Checkbox?
            $remember = isset($_POST['remember']) ? TRUE : FALSE;
            
            // Use Auth to login the user
            Auth::instance()->login($_POST['username'], $_POST['password'], $remember);
			
            if (!Auth::instance()->logged_in()) {
            	//we set erros binded in content.
				Notice::error(__('Please use a valid username and password'),__('Login failed.'),'login');
                $errors = array( __('Login failed.  Please use a valid username and password.'));
                return;
            }
            
			//we bind user to view globally.
			$user = Auth::instance()->get_user();
			
            //go to the home page if successful
           // $this->_redirect_user('redirect_on_login');
           $default_redirect = Kohana::$config->load('admin.default_redirect');
           $redirect = Session::instance()->get('admin.redirect',$default_redirect);		   
		   $this->request->redirect($redirect);
        }
	}
	
	/**
	 * 
	 */
	public function action_logout()
	{
    	// If user is not logged in, redirect to home page
        if ( ! Auth::instance()->logged_in())
		{            
            $this->_redirect_user();
        }
		
        View::bind_global('user',$user);
		
		if (isset($_POST['logout']) || isset($_GET['logout'])) 
		{
        	$this->action_dologout();
		}
    }
	
	/**
	 * 
	 */
	public function action_dologout()
	{
        // Redirect to the home page
		$this->_redirect_user('redirect_logout');
		
		// If a user lands on this page, assume he wants to do a fresh login
		Auth::instance()->logout();
		Cookie::delete('user');
	}
	
	/**
	 * 
	 */
	public function action_noaccess()
	{
		// Load the login page
        $this->template->title = __('Access Denied');
        $this->template->content->bind('redirect', $redirect);
		
		View::bind_global('user',$user);
		
		$user = Auth::instance()->get_user();
		$redirect = '/'; //Kohana::config('');
	}
	
	protected function _redirect_user($uri = NULL,array $params = NULL )
	{
		//we can use action to determine which redirect to use.
		//$action = Request::instance()->action;		
		//$redirect = $uri ? Kohana::config($this->_config_id.$uri) : '/';
		$redirect = $uri ? Route::url($uri, $params, FALSE) : '/';
		//we should check is valid uri:
		
		Request::instance()->redirect($redirect);
	}
	
	/**
	 * 
	 */
	public function action_setup()
	{
		$password = $this->request->param('password', FALSE);
		if($password === FALSE || $password !== Kohana::$config->load('emb-admin')->setup['password'])
		{
			/*
			 * We need a password in our URL. If we don't have it, we made 
			 * it here through the default route, bad.
			 * Else, we got here with wrong password.
			 * Bounce the fuck out!
			 */
			$this->request->redirect('/');
		} 
		
		$out  = "<p>";
		$out .= "Setting up Admin module. DDBB and inital user creation.<br/>";
		//Here, we check for the ddbb, and setup the needed tables.
		
		//check if we have admin user.
		$user = ORM::factory('user',array('username' => 'admin'));
		$out .= "User admin present.<br/>";
		
		//a95414f443b9dcff2a34c39e3635f41a0ff10a27636576a7ca8b258e6facbdc9
		
		if(!$user->loaded())
		{
			$user = ORM::factory('user');
			$user->values(array(
			   'username' => 'admin',
			   'email' => 'admin@example.com',
			   'password' => 'admin',
			   'password_confirm' => 'admin',
			));
			$user->save();
			
			$out = "User admin created.<br/>";
		}
		
		// remember to add the login role AND the admin role
		$created_roles = array();
		$needed_roles  = array('login','admin');
		$loaded_roles  = $user->roles->find_all()->as_array();
		
		$out .= "Current roles: ".count($loaded_roles)."<br/>";
		
		foreach($loaded_roles as $role)
		{
			array_push($created_roles,$role->name);
		}
		
		foreach($needed_roles as $role_name)
		{
			if(in_array($role_name,$created_roles,TRUE))
			{
				$out .= "Role <strong>{$role_name}</strong> already present. Skeep.<br/>";
				continue;
			}
			
			$role = ORM::factory('role')->where('name', '=', $role_name)->find( );
			$out .= "Role <strong>{$role->name}</strong> found: {$role->description}<br/>";
			$user->add('roles', $role);			
			$out .= "Role {$role->name} <strong>not present</strong>. Created.<br/>";
		}
  		
		$out .= "<a href='".Route::url('emb-admin',array('action' => 'login'),TRUE)."'> + Log in now!</a>";
		$out .= "</p>";
		
		$this->template->content->set('content',$out);
	}
}
