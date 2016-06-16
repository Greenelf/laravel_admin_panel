<?php
namespace Greenelf\Panel\libs;


use Greenelf\Panel\Role;
use Greenelf\Panel\RolePermission;

class dashboard
{

    public static $dashboardItems;

    public static $urls;

    public static function getItems()
    {
        if(!self::$dashboardItems) {
            self::$dashboardItems = self::create();
        }

        return self::$dashboardItems;
    }

    public static function create()
    {
        self::$urls = \Config::get('panel.panelControllers');

        $config    = \Greenelf\Panel\Link::allCached();
        $dashboard = array();

        $appHelper = new AppHelper();
        $test = new RolePermission();
        // Make Dashboard Items
        foreach ($config as $value) {

    	    $modelName = $value['url'];

            if ( in_array($modelName, self::$urls)) {
               $model = "Greenelf\\Panel\\".$modelName;
            } else {
               $model = $appHelper->getNameSpace() . $modelName;
            }

            //if (class_exists($value)) {
            $dashboard[] = array(
                'modelName' => $modelName,
                'title'	  => $value['display'],
                'count'	  => $model::count(),
                'showListUrl' => 'panel/' . $modelName . '/all',
                'addUrl'	  => 'panel/' . $modelName . '/edit',
            );
        }

	   return $dashboard;
    }
}
