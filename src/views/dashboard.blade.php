@extends('panelViews::mainTemplate')
@section('page-wrapper')

    <div class="row">

        <div class="col-lg-12">
            <h1 class="page-header">{{ \Lang::get('panel::fields.dashboard') }}</h1>
            <div class="icon-bg ic-layers"></div>
        </div>

    </div>
    <!-- /.row -->
    <div class="row box-holder">

        @if(is_array(\Greenelf\Panel\Link::returnUrls()))
            @foreach (Greenelf\Panel\libs\dashboard::getItems() as $box)
                <div class="col-lg-3 col-md-6">
                    <div class="panel ">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-7 title">
                                    {{$box['title']}}
                                </div>
                                <div class="col-xs-5 text-right">
                                    <div class="huge">{{$box['count']}}</div>
                                    <div></div>

                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">

                            <a href='{{$box['showListUrl']}}' class="pull-left">{{ \Lang::get('panel::fields.showList') }} <i class="icon ic-chevron-right"></i></a>
                            <div class="pull-right"> <a class="add " href="{{$box['addUrl']}}">{{ \Lang::get('panel::fields.Add') }}  </a></div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif


    </div>
    <div class="row hide update">
        <div class="alert alert-warning" role="alert">
            <a href="https://github.com/Greenelf/laravel_admin_panel" class="alert-link"></a>
        </div>
    </div>

@stop
