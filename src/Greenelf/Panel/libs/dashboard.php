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
        if (!self::$dashboardItems) {
            self::$dashboardItems = self::create(null);
        }

        return self::$dashboardItems;
    }


    public static function create($url)
    {
        //dd($url);
        self::$urls = \Config::get('panel.panelControllers');

        $config = \Greenelf\Panel\Link::allCached();
        $dashboard = array();

        $appHelper = new AppHelper();

        // Make Dashboard Items
        foreach ($config as $value) {
            $active = '';
            if($value['parent_id'] == 0){
                $modelName = $value['url'];

                if (in_array($modelName, self::$urls)) {
                    $model = "Greenelf\\Panel\\" . $modelName;
                    $countModelItems = $model::count();
                    $showListUrl = 'panel/' . $modelName . '/all';
                    if($showListUrl ==$url){
                        $active = 'active';
                    }
                    $addUrl = 'panel/' . $modelName . '/edit';
                } else {
                    $model = $appHelper->getNameSpace() . $modelName;
                    if (class_exists($model)) {
                        $countModelItems = $model::count();
                        $showListUrl = 'panel/' . $modelName . '/all';
                        if($showListUrl ==$url){
                            $active = 'active';
                        }
                        $addUrl = 'panel/' . $modelName . '/edit';
                    } else {
                        $modelName = $value['url'];
                        $countModelItems = '';
                        $showListUrl = $value['url'];
                        if($showListUrl ==$url){
                            $active = 'active';
                        }
                        $addUrl = '';
                    }
                }
                $this_dashboard = new dashboard();
                $childItems = $this_dashboard->createChild($config, $value['id'], $url);
                foreach($childItems as $childItem){
                    if($childItem['active'] == 'active'){
                        $active = 'active';
                    }
                }
                $dashboard[] = array(
                    'modelName' => $modelName,
                    'title' => $value['display'],
                    'count' => $countModelItems,
                    'showListUrl' => $showListUrl,
                    'addUrl' => $addUrl,
                    'id' => $value['id'],
                    'parent_id' => $value['parent_id'],
                    'childMenus' => $childItems,
                    'active' => $active,
                );
            }
        }
        return $dashboard;
    }

    public function createChild($urls, $parent_id, $url){
        $childItems = [];
        $appHelper = new AppHelper();
        foreach($urls as $childItem){
            $active = '';
            if($childItem['parent_id'] == $parent_id){
                $modelName = $childItem['url'];

                if (in_array($modelName, self::$urls)) {
                    $model = "Greenelf\\Panel\\" . $modelName;
                    $countModelItems = $model::count();
                    $showListUrl = 'panel/' . $modelName . '/all';
                    if($showListUrl ==$url){
                        $active = 'active';
                    }
                    $addUrl = 'panel/' . $modelName . '/edit';
                } else {
                    $model = $appHelper->getNameSpace() . $modelName;
                    if (class_exists($model)) {
                        $countModelItems = $model::count();
                        $showListUrl = 'panel/' . $modelName . '/all';
                        if($showListUrl ==$url){
                            $active = 'active';
                        }
                        $addUrl = 'panel/' . $modelName . '/edit';
                    } else {
                        $modelName = $childItem['url'];
                        $countModelItems = '';
                        $showListUrl = $childItem['url'];
                        if($modelName ==$url){
                            $active = 'active';
                        }
                        $addUrl = '';
                    }
                }
                $childItems[] = array(
                    'modelName' => $modelName,
                    'title' => $childItem['display'],
                    'count' => $countModelItems,
                    'showListUrl' => $showListUrl,
                    'addUrl' => $addUrl,
                    'id' => $childItem['id'],
                    'parent_id' => $childItem['parent_id'],
                    'active' => $active,
                );
            }
        }
        return $childItems;
    }
}
