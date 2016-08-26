@extends('panelViews::master')
@section('bodyClass', 'dashboard')
@section('body')
    <?php
    $urls = \Config::get('panel.panelControllers');
    $dashboard = new \Greenelf\Panel\libs\dashboard();
    $linkItems = $dashboard->getMenuUrls(Request::path());
<<<<<<< HEAD
    $admin = \Auth::guard('panel')->user();
=======
>>>>>>> 0ef8fc729307ffdb815e35e98d49af3769e542ec
    //$linkItems = \Greenelf\Panel\libs\dashboard::create(Request::path());
    ?>

    <div class="loading">
        <h1> LOADING </h1>
        <div class="spinner">
            <div class="rect1"></div>
            <div class="rect2"></div>
            <div class="rect3"></div>
            <div class="rect4"></div>
            <div class="rect5"></div>
        </div>
    </div>

    <div id="wrapper">
        <!-- Navigation -->

<<<<<<< HEAD
        <nav class="navbar navbar-default navbar-static-top " role="navigation"
             style="margin-bottom: 0">

            <!-- /.navbar-header -->
            <div class="navbar-header">
                <button type="button"
                        class="navbar-toggle collapsed btn-resp-sidebar"
                        data-toggle="collapse"
=======
        <nav class="navbar navbar-default navbar-static-top " role="navigation" style="margin-bottom: 0">

            <!-- /.navbar-header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed btn-resp-sidebar" data-toggle="collapse"
>>>>>>> 0ef8fc729307ffdb815e35e98d49af3769e542ec
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>

            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar " role="navigation">
<<<<<<< HEAD
                <div class="sidebar-nav navbar-collapse collapse "
                     id="bs-example-navbar-collapse-1">
                <!--<div class="grav center"><img src="http://www.gravatar.com/avatar/{{ md5( strtolower( trim( Auth::guard('panel')->user()->email ) ) )}}?d=mm&s=128" ><a href="https://www.gravatar.com"><span> {{ \Lang::get('panel::fields.change') }}</span></a></div>-->
                    <div class="panel-logo"><a href="{{$app['url']->to('/')}}"
                                               title="{{ \Lang::get('panel::fields.visiteSite') }}"><img
                                    src="/img/panel-logo.gif" alt="Money24"></a>
                    </div>
=======
                <div class="sidebar-nav navbar-collapse collapse " id="bs-example-navbar-collapse-1">
                <!--<div class="grav center"><img src="http://www.gravatar.com/avatar/{{ md5( strtolower( trim( Auth::guard('panel')->user()->email ) ) )}}?d=mm&s=128" ><a href="https://www.gravatar.com"><span> {{ \Lang::get('panel::fields.change') }}</span></a></div>-->
                    <div class="panel-logo"><a href="{{$app['url']->to('/')}}" title="{{ \Lang::get('panel::fields.visiteSite') }}"><img src="/img/panel-logo.gif" alt="Money24"></a></div>
>>>>>>> 0ef8fc729307ffdb815e35e98d49af3769e542ec
                    <div class="user-info">{{Auth::guard('panel')->user()->first_name.' '.Auth::guard('panel')->user()->last_name}}</div>
                    <a class="visit-site"
                       href="{{route('CustomDashboard')}}">Главная панель</a>


                    <ul class="nav navbar-nav side-nav left-menu">
                    @foreach($linkItems as $item)
                        <!--<li>
                                <a href="index.html"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="charts.html"><i class="fa fa-fw fa-bar-chart-o"></i> Charts</a>
                            </li>
                            <li>
                                <a href="tables.html"><i class="fa fa-fw fa-table"></i> Tables</a>
                            </li>
                            <li class="active">
                                <a href="forms.html"><i class="fa fa-fw fa-edit"></i> Forms</a>
                            </li>
                            <li>
                                <a href="bootstrap-elements.html"><i class="fa fa-fw fa-desktop"></i> Bootstrap Elements</a>
                            </li>
                            <li>
                                <a href="bootstrap-grid.html"><i class="fa fa-fw fa-wrench"></i> Bootstrap Grid</a>
                            </li>-->
                            <li>
<<<<<<< HEAD
                                <a href="javascript:;" data-toggle="collapse"
                                   data-target="#menu{{$item['id']}}" class=""
                                   aria-expanded="true"><i
                                            class="{{$item['icon']}}"></i> {{$item['title']}}
                                    <i class="fa fa-fw fa-caret-down"></i></a>
=======
                                <a href="javascript:;" data-toggle="collapse" data-target="#menu{{$item['id']}}" class="" aria-expanded="true"><i class="fa fa-fw fa-arrows-v"></i> {{$item['title']}} <i class="fa fa-fw fa-caret-down"></i></a>
>>>>>>> 0ef8fc729307ffdb815e35e98d49af3769e542ec
                                <ul
                                        id="menu{{$item['id']}}"
                                        @if($item['isActive'])
                                        class="collapse in active"
                                        @else
                                        class="collapse"
                                        @endif
                                        aria-expanded="true"
                                >
                                    @foreach($item['childMenus'] as $childItem)
                                        <li
                                                @if($childItem['isActive'])
                                                class="active"
                                                @endif
                                        >
                                            <a href="{{asset($childItem['showListUrl'])}}">{{$childItem['title']}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <!--
                            <li>
                                <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
                            </li>
                            <li>
                                <a href="index-rtl.html"><i class="fa fa-fw fa-dashboard"></i> RTL Dashboard</a>
                            </li>-->
                        @endforeach
                    </ul>
                </div>

            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <script>
            $(function () {
<<<<<<< HEAD
                $('#sidebar_menu').metisMenu({doubleTapToGo: true});
            });

        </script>
        <div class="powered-by"><a
                    href="https://github.com/Greenelf/laravel_admin_panel">{{ \Lang::get('panel::fields.thankYouNote') }}</a>
        </div>
        <div id="page-wrapper">
            <!-- Menu Bar -->
            <div class="row">
                <div class="col-xs-12 text-a top-icon-bar">
                    <ul class="nav navbar-right top-nav right-top-menu">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle"
                               data-toggle="dropdown"><i
                                        class="fa fa-envelope"></i> <b
                                        class="caret"></b></a>
                            <ul class="dropdown-menu message-dropdown">
                                <li class="message-preview">
                                    <a href="#">
                                        <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object"
                                             src="http://placehold.it/50x50"
                                             alt="">
                                    </span>
                                            <div class="media-body">
                                                <h5 class="media-heading">
                                                    <strong>John Smith</strong>
                                                </h5>
                                                <p class="small text-muted"><i
                                                            class="fa fa-clock-o"></i>
                                                    Yesterday at 4:32 PM</p>
                                                <p>Lorem ipsum dolor sit amet,
                                                    consectetur...</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="message-preview">
                                    <a href="#">
                                        <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object"
                                             src="http://placehold.it/50x50"
                                             alt="">
                                    </span>
                                            <div class="media-body">
                                                <h5 class="media-heading">
                                                    <strong>John Smith</strong>
                                                </h5>
                                                <p class="small text-muted"><i
                                                            class="fa fa-clock-o"></i>
                                                    Yesterday at 4:32 PM</p>
                                                <p>Lorem ipsum dolor sit amet,
                                                    consectetur...</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="message-preview">
                                    <a href="#">
                                        <div class="media">
                                    <span class="pull-left">
                                        <img class="media-object"
                                             src="http://placehold.it/50x50"
                                             alt="">
                                    </span>
                                            <div class="media-body">
                                                <h5 class="media-heading">
                                                    <strong>John Smith</strong>
                                                </h5>
                                                <p class="small text-muted"><i
                                                            class="fa fa-clock-o"></i>
                                                    Yesterday at 4:32 PM</p>
                                                <p>Lorem ipsum dolor sit amet,
                                                    consectetur...</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="message-footer">
                                    <a href="#">Read All New Messages</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" id="alert_bell" class="dropdown-toggle"
                               data-toggle="dropdown" aria-expanded="false"><i
                                        class="fa fa-bell"></i> <b
                                        class="caret"></b></a>
                            <ul class="dropdown-menu alert-dropdown">
                                <!--<li class="main-alert">
                                    <a href="#">Alert Name <span
                                                class="label label-default">Alert Badge</span></a>
                                </li>
                                <li class="main-alert">
                                    <a href="#">Alert Name <span
                                                class="label label-primary">Alert Badge</span></a>
                                </li>
                                <li class="main-alert">
                                    <a href="#">Alert Name <span
                                                class="label label-success">Alert Badge</span></a>
                                </li>
                                <li class="main-alert">
                                    <a href="#">Alert Name <span
                                                class="label label-info">Alert Badge</span></a>
                                </li>
                                <li class="main-alert">
                                    <a href="#">Alert Name <span
                                                class="label label-warning">Alert Badge</span></a>
                                </li>
                                <li class="main-alert">
                                    <a href="#">Alert Name <span
                                                class="label label-danger">Alert Badge</span></a>
                                </li>
                                <!--<li class="divider"></li>
                                <li class="main-alert">
                                    <a href="#">View All</a>
                                </li>-->
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle"
                               data-toggle="dropdown"><i
                                        class="fa fa-user"></i> {{$admin->last_name}} {{$admin->first_name}}
                                <b
                                        class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{url('panel/edit')}}"><i
                                                class="fa fa-fw fa-user"></i>{{ Lang::get('panel::fields.ProfileEdit') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{url('panel/changePassword')}}"><i
                                                class="fa fa-fw fa-key"></i>{{ Lang::get('panel::fields.ChangePassword') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="#"><i
                                                class="fa fa-fw fa-envelope"></i>
                                        Inbox</a>
                                </li>
                                <li>
                                    <a href="#"><i class="fa fa-fw fa-gear"></i>
                                        Settings</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="{{url('panel/logout')}}"><i
                                                class="fa fa-fw fa-power-off"></i>{{ Lang::get('panel::fields.logout') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
=======
                $('#sidebar_menu').metisMenu({ doubleTapToGo: true });
            });

        </script>
        <div class="powered-by"><a href="https://github.com/Greenelf/laravel_admin_panel">{{ \Lang::get('panel::fields.thankYouNote') }}</a>
        </div>
        <div id="page-wrapper">


            <!-- Menu Bar -->
            <div class="row">
                <div class="col-xs-12 text-a top-icon-bar">
                    <div class="btn-group" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                            <a type="button" class="btn btn-default dropdown-toggle main-link" data-toggle="dropdown"
                               aria-expanded="false">
                                {{ Lang::get('panel::fields.settings') }}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{url('panel/edit')}}"><span
                                                class="icon  ic-users "></span>{{ Lang::get('panel::fields.ProfileEdit') }}
                                    </a></li>
                                <li><a href="{{url('panel/changePassword')}}"><span
                                                class="icon ic-cog"></span>{{ Lang::get('panel::fields.ChangePassword') }}
                                    </a></li>
                            </ul>
                        </div>
                        <a href="{{url('panel/logout')}}" type="button"
                           class="btn btn-default main-link">{{ Lang::get('panel::fields.logout') }}<span
                                    class="icon  ic-switch"></span></a>
                    </div>
>>>>>>> 0ef8fc729307ffdb815e35e98d49af3769e542ec
                </div>
            </div>

            @yield('page-wrapper')

        </div>
    </div>
    <!-- /#page-wrapper -->

    </div>
@stop
