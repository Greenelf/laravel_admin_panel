<?php

namespace Greenelf\Panel;

class LinkController extends CrudController
{
    public function all($entity)
    {
        parent::all($entity);

        $this->filter = \DataFilter::source(new Link());
        $this->filter->add('id', 'ID', 'text');
        $this->filter->add('display', 'Display', 'text');
        $this->filter->submit('search');
        $this->filter->reset('reset');
        $this->filter->build();

        $this->grid = \DataGrid::source($this->filter);
        $this->grid->add('id', 'ID', true)->style("width:100px");
        $this->grid->add('display', 'Display');
        $this->grid->add('url', 'Model');

        $this->addStylesToGrid();

        return $this->returnView();
    }

    public function edit($entity)
    {
        parent::edit($entity);

        $this->edit = \DataEdit::source(new Link());

        $helpMessage = \Lang::get('panel::fields.links_help');

        $this->edit->label('Edit Links');
        $this->edit->link("rapyd-demo/filter", "Articles", "TR")->back();
        $this->edit->add('display', 'Display', 'text')->rule('required');
        $this->edit->add('url', 'link', 'text')->rule('required');
        $this->edit->add('type', 'Type', 'radiogroup')
            ->option('model', 'Model')->option('controller', 'Controller');
        $this->edit->add('parent_id', 'Parent ID', 'text')->rule('required');
        $this->edit->add('parent_id','Parent menu','select')->options(Link::lists("display", "id")->all());

        $this->addHelperMessage($helpMessage);

        return $this->returnEditView();
    }
}
