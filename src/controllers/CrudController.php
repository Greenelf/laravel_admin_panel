<?php
namespace Greenelf\Panel;

use Illuminate\Routing\Controller;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Facades\App;

class CrudController extends Controller
{
    const ID_COLUMN = 'id';

    public $grid;
    public $entity;
    public $set;
    public $edit;
    public $filter;
    protected $lang;
    public $helper_message;
    private $model;

    public function __construct(\Lang $lang)
    {
        $route = \App::make('route');
        $this->lang = $lang;
        $this->route = $route;
        if ($route = $route::current()) {
            $routeParamters = $route->parameters();
            if (isset($routeParamters['entity']))
                $this->setEntity($routeParamters['entity']);
        }
    }

    /**
     * @param string $entity name of the entity
     */
    public function all($entity)
    {
        $this->setModel($entity);
    }

    /**
     * @param string $entity name of the entity
     */
    public function edit($entity)
    {

    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntityModel()
    {
        $entity = $this->getEntity();
        //TODO for delete
        //$appHelper = new libs\AppHelper;

        if (in_array($entity, Link::getPanelModels())) {
            $modelClass = 'Greenelf\\Panel\\' . $entity;
        } else {
            $modelClass = $this->findModel($entity);
            //TODO for delete
            //$modelClass = $appHelper->getNameSpace() . $this->getEntity();
        }

        if(!$modelClass){
            $modelClass = $this->model;
        }

        return new $modelClass;
    }

    private function findModel($modelName)
    {
        $finder = new Finder();
        $files = $finder->files()->name($modelName . ".php")->in(App::basePath() . '/app');
        foreach ($files as $item) {
            $fileContent = $item->getContents();
            return $modelClass = $this->getNameSpace($fileContent) . "\\" . $modelName;
        }
        return false;
    }

    public function setModel($modelName)
    {
        $model = $this->findModel($modelName);
        $this->model = $model;
        return $model;
    }

    public function addStylesToGrid($orderByColumn = self::ID_COLUMN, $paginateCount = 10)
    {
        $this->grid->edit('edit', trans('panel::fields.edit'), 'show|modify|delete');


       // if ($orderByColumn === self::ID_COLUMN) {
       //     $orderByColumn = $this->getEntityModel()->getKeyName();
       // }
        //TODO for delete

        $this->grid->orderBy($orderByColumn, 'desc');
        $this->grid->paginate($paginateCount);
    }

    public function addHelperMessage($message)
    {
        $this->helper_message = $message;
    }

    public function returnView()
    {
        $configs = Link::returnUrls();

        if (!isset($configs) || $configs == null) {
            throw new \Exception('NO URL is set yet !');
        } else if (!in_array($this->entity, $configs)) {
            throw new \Exception('This url is not set yet!');
        } else {
            return view('panelViews::all', array(
                'grid' => $this->grid,
                'filter' => $this->filter,
                'title' => $this->entity,
                'current_entity' => $this->entity,
                'import_message' => (\Session::has('import_message')) ? \Session::get('import_message') : ''
            ));
        }
    }

    public function returnEditView()
    {
        $configs = Link::returnUrls();

        if (!isset($configs) || $configs == null) {
            throw new \Exception('NO URL is set yet !');
        } else if (!in_array($this->entity, $configs)) {
            throw new \Exception('This url is not set yet !');
        } else {
            return view('panelViews::edit', array('title' => $this->entity,
                'edit' => $this->edit,
                'helper_message' => $this->helper_message));
        }
    }

    public function finalizeFilter()
    {
        \App::make('lang');
        $this->filter->submit($this->lang->get('panel::fields.search'));
        $this->filter->reset($this->lang->get('panel::fields.reset'));
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
