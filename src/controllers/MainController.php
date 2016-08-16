<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Greenelf\Panel;

use Greenelf\Panel\libs\PanelElements;
use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use Illuminate\Support\ClassLoader;
use Illuminate\Support\Facades\App;
use Psy\Autoloader;
use Symfony\Component\Finder\Finder;


class MainController extends Controller
{
    public function entityUrl($entity, $methods)
    {
        //TODO for delete
        //$appHelper = new libs\AppHelper();

        //$urls = Link::getModelUrls();
        $panelUrls = Link::getPanelModels();

        if (in_array($entity, $panelUrls)) {
            $nameSpace = 'Greenelf\Panel\\' . $entity . 'Controller';
        } else {
            $finder = new Finder();
            $files = $finder->files()->name($entity . "Controller.php")->in(App::basePath() . '/app');
            foreach ($files as $item) {
                $fileContent = $item->getContents();
                $nameSpace = $this->getNameSpace($fileContent) . "\\" . $entity . "Controller";
            }
            //TODO for delete
            //$controller_path = $appHelper->getNameSpace() . 'Http\Controllers\\' . $entity . 'Controller';
        }

        try {
            $controller = \App::make($nameSpace);
        } catch (\Exception $ex) {
            throw new \Exception("Can not found the Controller ( $nameSpace ) ");
        }

        if (!method_exists($controller, $methods)) {
            throw new \Exception('Controller does not implement the CrudController methods!');
        } else {
            return $controller->callAction($methods, array('entity' => $entity));
        }
    }

    /**
     * @param $fileContent
     * @return null or string $nameSpace
     */
    private function getNameSpace($fileContent)
    {
        if (preg_match('#^namespace\s+(.+?);$#sm', $fileContent, $m)) {
            return $m[1];
        }
        return null;
    }
}


