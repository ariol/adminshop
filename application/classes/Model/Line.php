<?php defined('SYSPATH') or die('No direct script access.');

class Model_Line extends ORM
{
    protected $_table_name = 'line';
        protected $_has_many = array(
        'product' => array(
            'model' => 'Product',
            'foreign_key' => 'product_id',
        )
    );
        protected $_grid_columns = array(
        'name' => null,
        'active' => 'bool',
        'delete' => array(
            'width' => '50',
            'type' => 'link',
            'color' => 'red',
            'route_str' => 'admin-line:delete?id=${id}',
            'title' => '<i class="fa fa-trash-o"></i>',
            'color' => 'red',
            'alternative' => 'Удалить',
            'confirm' => 'Вы уверены?'
        )
    );
        public function labels()
    {
        return array(
            'name' => 'Название',
            'url' => 'URL',
            'active' => 'Активность'
        );
    }

    public function form()
    {
        return new Form_Admin_Line($this);
    }
    public function selectInsert($name)
    {
        $line = ORM::factory('Line')->where('name', '=', $name)->find();
        if (!$line->loaded()) {
            $url = Helpers_Url::translit($name);

            $line = ORM::factory('Line');
            $line->name = $name;
            $line->url = $url;
            $line->md5_url = md5($url);
            $line->save();
        }
        return $line->id;
    }


}