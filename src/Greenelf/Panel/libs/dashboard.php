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

    private $menuItems;
    private $currentPath;

    private function getPartUrl(&$menuItem)
    {
        switch($menuItem->type){
            case 'model':
                return $menuItem->model;
            case 'controller':
                return $menuItem->controller;
            default:
                return $menuItem->model;
        }
    }

    private function getMainItems()
    {
        $menuItems = $this->menuItems;
        $mainMenuItems = $menuItems->filter(function ($item) {
            return $item->parent_id == 0;
        });
        return $mainMenuItems;
    }

    private function getChildmenuItems($parentID)
    {
        $menuItems = $this->menuItems;
        $childMenuItems = $menuItems->where('parent_id', $parentID, $strict=false);
        $childItems = [];
        foreach ($childMenuItems as $menuItem){
            $childItems[] = $this->addMenuItem($menuItem);
        }
        return $childItems;
    }

    private function addMenuItem(&$menuItem)
    {
        $childMenus = $this->getChildmenuItems($menuItem->id);
        $isActive = $this->isActive($menuItem);
        return [
            'modelName' => $menuItem->model,
            'title' => $menuItem->display,
            'count' => 10,
            'showListUrl' => $this->setShowListUrl($menuItem),
            'addUrl' => $this->setEditUrl($menuItem),
            'id' => $menuItem->id,
            'childMenus' => $childMenus,
            'parent_id' => $menuItem->parent_id,
            'isActive' => $isActive,
            'icon' => $menuItem->icon
        ];
    }

    private function setShowListUrl(&$menuItem)
    {
        $partUrl = $this->getPartUrl($menuItem);
        return 'panel/' . $partUrl . '/all';
    }

    private function setEditUrl(&$menuItem)
    {
        $partUrl = $this->getPartUrl($menuItem);
        return 'panel/' . $partUrl . '/edit';
    }

    private function isActive(&$menuItem)
    {
        if($menuItem->parent_id == 0){
            return $this->isActiveMainMenu($menuItem->id);
        }else{
            return $this->isActiveMenu($menuItem);
        }
    }

    private function isActiveMainMenu($parentID)
    {
        $childMenus = $this->menuItems->where('parent_id', $parentID);

        foreach ($childMenus as $item){
            if($this->isActiveMenu($item)){
                return true;
            }
        }
        return false;
    }

    private function isActiveMenu(&$menuItem)
    {
        $showListUrl = $this->setShowListUrl($menuItem);
        $editUrl = $this->setEditUrl($menuItem);
        $path = $this->currentPath;
        if($showListUrl == $path
            or $editUrl == $path){
            return true;
        }
        return false;
    }

    public function getMenuUrls($path)
    {
        $menuItems = \Greenelf\Panel\Link::all();
        $this->menuItems = $menuItems;
        $this->currentPath = $path;
        $mainMenuItems = $this->getMainItems();
        foreach ($mainMenuItems as $menuItem){
            $mainMenuItemsUrls[] = $this->addMenuItem($menuItem);
        }

        return $mainMenuItemsUrls;
    }
    //TODO remove
/*
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
    */
}
