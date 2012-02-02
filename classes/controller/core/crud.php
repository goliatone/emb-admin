<?php defined('SYSPATH') or die('No direct script access.');
/**
 *
 * @package    	Product
 * @category	Admin
 * @author 		Emiliano Burgos <hello@goliatone.com>
 * @copyright  	(c) 20011 Emiliano Burgos
 */
class Controller_Core_Crud extends Controller_Backend
{

	protected $_resource = 'category';

	protected $_route_name = 'emb-admin-controller';
	
	public function after()
	{
		$this->template->content->set("resource",$this->_resource);
		parent::after();
	}
	
	
	public function action_index()
	{
		$elements = ORM::Factory($this->_resource)->find_all();

		$link_new = $this->generate_link('new');

		$view = $this->template->content;
		$view->set('_resource',$this->_resource);
		$view->set("elements", $elements);
		$view->set("link_new", $link_new);
	}

	
	
	/*
	 * CRUD controller: READ
	 */
	public function action_show()
	{

	}
	
	/*
	 * CRUD controller: CREATE
	 */
	public function action_new()
	{
		$element = ORM::factory($this->_resource);

		$form = Formo::form()->orm('load', $element);
		//$form->set('attr', array('action' => URL::site(Request::current()->detect_uri())) );
		$form->add('save', 'submit', 'Create');

		if($form->load($_POST)->validate())
		{
			if($this->_create_passed($form, $element))
			{
				$element->save();
				$form->orm('save_rel', $element);

				$this->request->redirect(Route::get($this->_route_name)->uri( array('controller' => $this->controller)));
			}
		}
		else
		{
			$this->_create_error($form, $element);
		}

		$view = $this->template->content;
		$view->set("formo", $form);

	}
	
	/*
	 * CRUD controller: UPDATE
	 */
	public function action_edit()
	{
		$item_id = $this->request->param('params');

		$element = ORM::Factory($this->_resource, $item_id);

		$form = Formo::form()->orm('load', $element);
		$form->add('update', 'submit', 'Save');

		if($form->load($_POST)->validate())
		{
			if($this->_update_passed($form, $element))
			{
				$element->save();
				$form->orm('save_rel', $element);

				$this->request->redirect(Route::get($this->_route_name)->uri( array('controller' => $this->controller)));
			}
		}
		else
		{
			$this->_update_error($form, $element);
		}

		$view = $this->template->content;
		$view->set("formo", $form);
	}

	/*
	 * CRUD controller: DELETE
	 */
	public function action_delete()
	{
		$item_id = $this->request->param('params');
		$element = ORM::factory($this->_resource, $item_id);
		$form = Formo::form()->orm('load', $element);
		$form->add('update', 'submit', 'Delete');
		$form->add('id','hidden',$element->id);
		
		if($_POST)
		{
			if($_POST['id'] == $element->id)
			{
				$this->_before_delete($element);
				$element->delete();
				$this->_after_delete($element);
				
				$this->request->redirect(Route::get($this->_route_name)->uri( array('controller' => $this->controller)));
			}
		}
		
		$view = $this->template->content;
		$view->set("formo", $form);
	}
	
	private function generate_link($action ='index')
	{
		return Route::url($this->_route_name, array('controller' => $this->controller, 'action' => $action), TRUE);
	}
	
	/*
	 * This method is a hook for form validation in Create action.
	 * It fires when form validation has passed.
	 *
	 * @param Formo_Form $form Formo_Form object
	 * @return mixed
	 */
	protected function _create_passed(Formo_Form $form, ORM $element)
	{
		// You will probably want to extend this method
		return true;
	}

	protected function _create_error(Formo_Form $form, ORM $element)
	{
		return true;
	}

	/*
	 * This method is a hook for form validation in Update action.
	 * It fires when form validation has passed.
	 *
	 * @param Formo_Form $form Formo_Form object
	 * @return mixed
	 */
	protected function _update_passed(Formo_Form $form, ORM $element)
	{
		// You will probably want to extend this method
		return true;
	}

	/*
	 * This method is a hook for form validation in Update action.
	 * It fires when form validation has error.
	 *
	 * @param Formo_Form $form Formo_Form object
	 * @return mixed
	 */
	protected function _update_error(Formo_Form $form, ORM $element)
	{
		// You will probably want to extend this method
		return true;
	}
	
	protected function _before_delete(ORM $item = NULL)
	{
	
	}
	
	protected function _after_delete(ORM $item = NULL)
	{
		
	}
	
	
	public function get_layout()
	{
		return 'admin/layout';
	}

}
