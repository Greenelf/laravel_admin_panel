<?php

namespace Greenelf\Panel;

class PermissionController extends CrudController
{
    public function all($entity)
    {
        parent::all($entity);

        $this->filter = \DataFilter::source(new Permission());
        $this->filter->add('id', 'ID', 'text');
        $this->filter->add('name', 'Name', 'text');
        $this->filter->submit('search');
        $this->filter->reset('reset');
        $this->filter->build();

        $this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID', true)->style("width:100px");
        $this->grid->add('name', 'UrlModelAccess')->style('width:100px');
        $this->grid->add('label', 'Description');

        $this->addStylesToGrid();

        return $this->returnView();
    }

    public function edit($entity)
    {
        parent::edit($entity);

        $this->edit = \DataEdit::source(new Permission());

        $helpMessage = (\Lang::get('panel::fields.roleHelp'));

        $this->edit->label('Edit Permission');
        $this->edit->link("rapyd-demo/filter", "Articles", "TR")->back();
        $this->edit->add('name', 'Url', 'text')->rule('required');
        $this->edit->add('label', 'Description', 'text')->rule('required');
        $this->edit->add('label', 'Description', 'text')->rule('required');
        $this->edit->add('link_id','Menu','select')->insertValue(1)->options(\Greenelf\Panel\Link::lists("url", "id")->all());  // pre-select a value in a select box

        $this->edit->saved(function () use ($entity) {
            $this->edit->message(\Lang::get('panel::fields.dataSavedSuccessfull'));
            $this->edit->link('panel/Permission/all', 'Back');
        });

        $this->addHelperMessage($helpMessage);

        return $this->returnEditView();
    }
}
