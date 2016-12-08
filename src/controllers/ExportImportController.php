<?php

namespace Greenelf\Panel;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Finder\Finder;

class ExportImportController extends Controller
{
    protected $failed = false;

    private function getClass($entity){
        $urls = \Config::get('panel.panelControllers');

        if (in_array($entity, $urls)) {
            $className = "Greenelf\\Panel\\" . $entity;
        } else {
            $appHelper = new libs\AppHelper();
            $className = $appHelper->getNameSpace() . $entity;
        }

        if (!$this->_hasClass($className)) {
            $finder = new Finder();
            $files = $finder->files()->name($entity . ".php")->in(\App::basePath() . '/app');
            foreach ($files as $item) {
                $fileContent = $item->getContents();
                $nameSpace = $this->_getNameSpace($fileContent);
                    if ($nameSpace) {
                        $className = $nameSpace. "\\" . $entity ;
                    }
            }
        }
        return $className;
    }

    private function _hasClass($nameSpace)
    {
        try {
            $hasClass = \App::make($nameSpace);
        } catch (\Exception $ex) {
            return false;
        }
        return true;
    }

    private function _getNameSpace($fileContent)
    {
        if(!preg_match('/extends Model/', $fileContent)) {
           return null;
        }
        if (preg_match('#^namespace\s+(.+?);$#sm', $fileContent, $m)) {
            return $m[1];
        }
        return null;
    }

    public function export($entity, $fileType)
    {
        $className = $this->getClass($entity);
        $data = $className::all();
        if (strcmp($fileType, "excel") == 0) {
            \App::make('Excel');
            \Excel::create($entity, function ($excel) use ($data) {
                $excel->sheet('Sheet1', function ($sheet) use ($data) {
                    $sheet->fromModel($data);
                });
            })->export('xls');
        }
    }

    public function import($entity)
    {
        $className = $this->getClass($entity);
        $model = new $className;
        $table = $model->getTable();
        $columns = \Schema::getColumnListing($table);
        $key = $model->getKeyName();

        $notNullColumnNames = array();
        $notNullColumnsList = \DB::select(\DB::raw("SHOW COLUMNS FROM `" . $table . "` where `Null` = 'no'"));
        if (!empty($notNullColumnsList)) {
            foreach ($notNullColumnsList as $notNullColumn) {
                $notNullColumnNames[] = $notNullColumn->Field;
            }
        }

        $status = Input::get('status');

        $filePath = null;
        if (Input::hasFile('import_file') && Input::file('import_file')->isValid()) {
            $filePath = Input::file('import_file')->getRealPath();
        }

        if ($filePath) {

            \Excel::load($filePath, function ($reader) use ($model, $columns, $key, $status, $notNullColumnNames) {
                $this->importDataToDB($reader, $model, $columns, $key, $status, $notNullColumnNames);
            });
        }

        $importMessage = ($this->failed == true) ? \Lang::get('panel::fields.importDataFailure') : \Lang::get('panel::fields.importDataSuccess');

        return \Redirect::to('panel/' . $entity . '/all')->with('import_message', $importMessage);
    }

    public function importDataToDB($reader, $model, $columns, $key, $status, $notNullColumnNames)
    {
        $rows = $reader->toArray();
        $newData = array();
        $updatedData = array();

        // Check validation of values
        foreach ($rows as $row) {
            foreach ($notNullColumnNames as $notNullColumn) {
                if (!isset($row[$notNullColumn])) {
                    $this->failed = true;
                    break;
                }
            }
        }

        if (!$this->failed) {
            if ($status == 1) {
                $model->truncate();
            }
            foreach ($rows as $row) {
                if (!empty($row[$key])) {
                    $exists = $model->where($key, '=', $row[$key])->count();
                    if (!$exists) {
                        $values = array();
                        foreach ($columns as $col) {
                            if ($col != $key) {
                                $values[$col] = $row[$col];
                            }
                        }
                        $newData[] = $values;
                    } else if ($status == 2 && $exists) {
                        $values = array();
                        foreach ($columns as $col) {
                            $values[$col] = $row[$col];
                        }
                        $updatedData[] = $values;
                    }
                }
            }
        }

        // insert data into table
        if (!empty($newData)) {
            $model->insert($newData);
        }

        // update available data
        if (!empty($updatedData)) {
            foreach ($updatedData as $data) {
                $keyValue = $data[$key];
                unset($data[$key]);
                $model->where($key, $keyValue)->update($data);
            }
        }
    }
}
