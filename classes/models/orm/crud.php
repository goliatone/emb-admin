<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		sumh <oalite@gmail.com>
 * @package		default
 * @copyright	(c) 2011 OALite team, All rights reserved.
 * @license		http://www.oalite.com/license.txt
 * @link		http://www.oalite.com
 * @see			ORM
 * *
 */
class Model_Orm_Crud extends ORM {
	
	protected $_label_name = "name";
	
	/**
	 * 
	 */
    public function lists(array $params, & $pagination = NULL, $calc_total = TRUE)
    {
        $pagination instanceOf Pagination OR $pagination = new Pagination;

        // Customize where from params
        //$this->where('', '', );

        // caculte the total rows
        if($calc_total === TRUE)
        {
            $pagination->total_items = $this->count_all();

            if($pagination->total_items === 0)
                return array();
        }

        // Customize order by from params
        if(isset($params['orderby']))
            $this->order_by(key($params['orderby']), current($params['orderby']));

        return $this->limit($pagination->items_per_page)
            ->offset($pagination->offset)
            ->find_all();
    }
	
	
	/**
	 * 
	 */
	public function __get($key)
	{
		if($key === "orm_primary_val") return $this->{$this->_label_name};
		else return parent::__get($key);
	}

} // END Model_Category
