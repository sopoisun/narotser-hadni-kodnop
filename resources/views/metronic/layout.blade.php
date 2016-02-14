<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>Pondok Indah | Software Restoran</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="MobileOptimized" content="320">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ url('/') }}/assets/metronic/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
   <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/metronic/plugins/bootstrap-toastr/toastr.min.css" />
   @yield('css_assets')
   <!-- END PAGE LEVEL STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="{{ url('/') }}/assets/metronic/css/style-metronic.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/style.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/style-responsive.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/metronic/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ url('/') }}/assets/metronic/css/custom.css" rel="stylesheet" type="text/css" />
    <!-- END THEME STYLES -->

    @yield('css_section')

    <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->

<body class="page-header-fixed">
    <!-- BEGIN HEADER -->
    <div class="header navbar navbar-inverse navbar-fixed-top">
        <!-- BEGIN TOP NAVIGATION BAR -->
        <div class="header-inner">
            <!-- BEGIN LOGO -->
            <a class="navbar-brand" href="index.html">
                <img src="{{ url('/') }}/assets/metronic/img/logo.png" alt="logo" class="img-responsive" />
            </a>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <img src="{{ url('/') }}/assets/metronic/img/menu-toggler.png" alt="" />
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <li class="dropdown" id="header_notification_bar">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-warning-sign"></i>
                        <span class="badge">6</span>
                    </a>
                    <ul class="dropdown-menu extended notification">
                        <li>
                            <p>You have 14 new notifications</p>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list scroller" style="height: 250px;">
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-success"><i class="icon-plus"></i></span> New user registered.
                                        <span class="time">Just now</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-danger"><i class="icon-bolt"></i></span> Server #12 overloaded.
                                        <span class="time">15 mins</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-warning"><i class="icon-bell"></i></span> Server #2 not responding.
                                        <span class="time">22 mins</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-info"><i class="icon-bullhorn"></i></span> Application error.
                                        <span class="time">40 mins</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-danger"><i class="icon-bolt"></i></span> Database overloaded 68%.
                                        <span class="time">2 hrs</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-danger"><i class="icon-bolt"></i></span> 2 user IP blocked.
                                        <span class="time">5 hrs</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-warning"><i class="icon-bell"></i></span> Storage Server #4 not responding.
                                        <span class="time">45 mins</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-info"><i class="icon-bullhorn"></i></span> System Error.
                                        <span class="time">55 mins</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="label label-sm label-icon label-danger"><i class="icon-bolt"></i></span> Database overloaded 68%.
                                        <span class="time">2 hrs</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="external">
                            <a href="#">See all notifications <i class="m-icon-swapright"></i></a>
                        </li>
                    </ul>
                </li>
                <!-- END NOTIFICATION DROPDOWN -->
                <!-- BEGIN INBOX DROPDOWN -->
                <li class="dropdown" id="header_inbox_bar">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-envelope"></i>
                        <span class="badge">5</span>
                    </a>
                    <ul class="dropdown-menu extended inbox">
                        <li>
                            <p>You have 12 new messages</p>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list scroller" style="height: 250px;">
                                <li>
                                    <a href="inbox.html?a=view">
                                        <span class="photo"><img src="{{ url('/') }}/assets/metronic/img/avatar2.jpg" alt=""/></span>
                                        <span class="subject">
                           <span class="from">Lisa Wong</span>
                                        <span class="time">Just Now</span>
                                        </span>
                                        <span class="message">
                           Vivamus sed auctor nibh congue nibh. auctor nibh
                           auctor nibh...
                           </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="inbox.html?a=view">
                                        <span class="photo"><img src="{{ url('/') }}/assets/metronic/img/avatar3.jpg" alt=""/></span>
                                        <span class="subject">
                           <span class="from">Richard Doe</span>
                                        <span class="time">16 mins</span>
                                        </span>
                                        <span class="message">
                           Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh
                           auctor nibh...
                           </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="inbox.html?a=view">
                                        <span class="photo"><img src="{{ url('/') }}/assets/metronic/img/avatar1.jpg" alt=""/></span>
                                        <span class="subject">
                           <span class="from">Bob Nilson</span>
                                        <span class="time">2 hrs</span>
                                        </span>
                                        <span class="message">
                           Vivamus sed nibh auctor nibh congue nibh. auctor nibh
                           auctor nibh...
                           </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="inbox.html?a=view">
                                        <span class="photo"><img src="{{ url('/') }}/assets/metronic/img/avatar2.jpg" alt=""/></span>
                                        <span class="subject">
                           <span class="from">Lisa Wong</span>
                                        <span class="time">40 mins</span>
                                        </span>
                                        <span class="message">
                           Vivamus sed auctor 40% nibh congue nibh...
                           </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="inbox.html?a=view">
                                        <span class="photo"><img src="{{ url('/') }}/assets/metronic/img/avatar3.jpg" alt=""/></span>
                                        <span class="subject">
                           <span class="from">Richard Doe</span>
                                        <span class="time">46 mins</span>
                                        </span>
                                        <span class="message">
                           Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh
                           auctor nibh...
                           </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="external">
                            <a href="inbox.html">See all messages <i class="m-icon-swapright"></i></a>
                        </li>
                    </ul>
                </li>
                <!-- END INBOX DROPDOWN -->
                <!-- BEGIN TODO DROPDOWN -->
                <li class="dropdown" id="header_task_bar">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <i class="icon-tasks"></i>
                        <span class="badge">5</span>
                    </a>
                    <ul class="dropdown-menu extended tasks">
                        <li>
                            <p>You have 12 pending tasks</p>
                        </li>
                        <li>
                            <ul class="dropdown-menu-list scroller" style="height: 250px;">
                                <li>
                                    <a href="#">
                                        <span class="task">
                           <span class="desc">New release v1.2</span>
                                        <span class="percent">30%</span>
                                        </span>
                                        <span class="progress">
                           <span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                           <span class="sr-only">40% Complete</span>
                                        </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="task">
                           <span class="desc">Application deployment</span>
                                        <span class="percent">65%</span>
                                        </span>
                                        <span class="progress progress-striped">
                           <span style="width: 65%;" class="progress-bar progress-bar-danger" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                           <span class="sr-only">65% Complete</span>
                                        </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="task">
                           <span class="desc">Mobile app release</span>
                                        <span class="percent">98%</span>
                                        </span>
                                        <span class="progress">
                           <span style="width: 98%;" class="progress-bar progress-bar-success" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100">
                           <span class="sr-only">98% Complete</span>
                                        </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="task">
                           <span class="desc">Database migration</span>
                                        <span class="percent">10%</span>
                                        </span>
                                        <span class="progress progress-striped">
                           <span style="width: 10%;" class="progress-bar progress-bar-warning" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                           <span class="sr-only">10% Complete</span>
                                        </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="task">
                           <span class="desc">Web server upgrade</span>
                                        <span class="percent">58%</span>
                                        </span>
                                        <span class="progress progress-striped">
                           <span style="width: 58%;" class="progress-bar progress-bar-info" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                           <span class="sr-only">58% Complete</span>
                                        </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="task">
                           <span class="desc">Mobile development</span>
                                        <span class="percent">85%</span>
                                        </span>
                                        <span class="progress progress-striped">
                           <span style="width: 85%;" class="progress-bar progress-bar-success" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                           <span class="sr-only">85% Complete</span>
                                        </span>
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <span class="task">
                           <span class="desc">New UI release</span>
                                        <span class="percent">18%</span>
                                        </span>
                                        <span class="progress progress-striped">
                           <span style="width: 18%;" class="progress-bar progress-bar-important" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">
                           <span class="sr-only">18% Complete</span>
                                        </span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="external">
                            <a href="#">See all tasks <i class="m-icon-swapright"></i></a>
                        </li>
                    </ul>
                </li>
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <li class="dropdown user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <img alt="" src="{{ url('/') }}/assets/metronic/user-avatar.png" />
                        <span class="username">{{ auth()->user()->karyawan->nama }}</span>
                        <i class="icon-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('/change-password') }}"><i class="icon-lock"></i> Change Password</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{{ url('/logout') }}" onclick="return confirm('Yakin Logout ??')"><i class="icon-key"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
            </ul>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END TOP NAVIGATION BAR -->
    </div>
    <!-- END HEADER -->
    <div class="clearfix"></div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="page-sidebar-menu">
                <li>
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                    <div class="sidebar-toggler hidden-phone"></div>
                    <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                </li>
                <li>
                    <!-- BEGIN RESPONSIVE QUICK SEARCH FORM --
                    <form class="sidebar-search">
                        <div class="form-container">
                            <div class="input-box">
                                <a href="javascript:;" class="remove"></a>
                                <input type="text" placeholder="Search..." />
                                <input type="button" class="submit" value=" " />
                            </div>
                        </div>
                    </form>
                    <!-- END RESPONSIVE QUICK SEARCH FORM -->
                </li>
                <li class="start ">
                    <a href="{{ url('/dashboard') }}">
                        <i class="icon-home"></i>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                @can('order.select_place')
                <li class="{{ set_active('order*') }}">
                    <a href="javascript:;">
                        <i class="icon-trophy"></i>
                        <span class="title">Order</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('order.select_place')
                        <li class="{{ set_active('order') }}">
                            <a href="{{ url('/order') }}">Open Order</a>
                        </li>
                        @endcan
                        @can('order.list')
                        <li class="{{ set_active('order/pertanggal') }}">
                            <a href="{{ url('/order/pertanggal') }}">Daftar Order</a>
                        </li>
                        @endcan
                        @can('order.list')
                        <li class="{{ set_active('order/pertanggal/return') }}">
                            <a href="{{ url('/order/pertanggal/return') }}">Daftar Order Return</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('report.pertanggal.penjualan')
                <li class="{{ set_active('report*') }}">
                    <a href="javascript:;">
                        <i class="icon-book"></i>
                        <span class="title">Laporan</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        <li class="{{ set_active('report/pertanggal*') }}">
                            <a href="javascript:;">
                            Laporan Pertanggal
                            <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @can('report.pertanggal.penjualan')
                                <li class="{{ set_active('report/pertanggal') }}"><a href="{{ url('/report/pertanggal') }}">Laporan Penjualan</a></li>
                                @endcan
                                @can('report.pertanggal.solditem')
                                <li class="{{ set_active('report/pertanggal/solditem') }}"><a href="{{ url('/report/pertanggal/solditem') }}">Laporan Sold Item</a></li>
                                <li class="{{ set_active('report/pertanggal/solditembahan') }}"><a href="{{ url('/report/pertanggal/solditembahan') }}">Laporan Sold Bahan</a></li>
                                @endcan
                                @can('report.pertanggal.karyawan')
                                <li class="{{ set_active('report/pertanggal/karyawan') }}"><a href="{{ url('/report/pertanggal/karyawan') }}">Laporan Karyawan</a></li>
                                @endcan
                                @can('report.pertanggal.labarugi')
                                <li class="{{ set_active('report/pertanggal/labarugi') }}"><a href="{{ url('/report/pertanggal/labarugi') }}">Laporan Laba/Rugi</a></li>
                                @endcan
                            </ul>
                        </li>
                        <li class="{{ set_active('report/periode*') }}">
                            <a href="javascript:;">
                            Laporan Perperiode
                            <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @can('report.periode.solditem')
                                <li class="{{ set_active('report/periode/solditem') }}"><a href="{{ url('/report/periode/solditem') }}">Laporan Sold Item</a></li>
                                <li class="{{ set_active('report/periode/solditembahan') }}"><a href="{{ url('/report/periode/solditembahan') }}">Laporan Sold Bahan</a></li>
                                @endcan
                                @can('report.periode.karyawan')
                                <li class="{{ set_active('report/periode/karyawan') }}"><a href="{{ url('/report/periode/karyawan') }}">Laporan Karyawan</a></li>
                                @endcan
                                @can('report.periode.labarugi')
                                <li class="{{ set_active('report/periode/labarugi') }}"><a href="{{ url('/report/periode/labarugi') }}">Laporan Laba/Rugi</a></li>
                                @endcan
                            </ul>
                        </li>
                        <li class="{{ set_active('report/perbulan*') }}">
                            <a href="javascript:;">
                            Laporan Perbulan
                            <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @can('report.perbulan.penjualan')
                                <li class="{{ set_active('report/perbulan') }}"><a href="{{ url('/report/perbulan') }}">Laporan Penjualan</a></li>
                                @endcan
                                @can('report.perbulan.solditem')
                                <li class="{{ set_active('report/perbulan/solditem') }}"><a href="{{ url('/report/perbulan/solditem') }}">Laporan Sold Item</a></li>
                                <li class="{{ set_active('report/perbulan/solditembahan') }}"><a href="{{ url('/report/perbulan/solditembahan') }}">Laporan Sold Bahan</a></li>
                                @endcan
                                @can('report.perbulan.karyawan')
                                <li class="{{ set_active('report/perbulan/karyawan') }}"><a href="{{ url('/report/perbulan/karyawan') }}">Laporan Karyawan</a></li>
                                @endcan
                                @can('report.perbulan.labarugi')
                                <li class="{{ set_active('report/perbulan/labarugi') }}"><a href="{{ url('/report/perbulan/labarugi') }}">Laporan Laba/Rugi</a></li>
                                @endcan
                            </ul>
                        </li>
                        <li class="{{ set_active('report/pertahun*') }}">
                            <a href="javascript:;">
                            Laporan Pertahun
                            <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @can('report.pertahun.penjualan')
                                <li class="{{ set_active('report/pertahun') }}"><a href="{{ url('/report/pertahun') }}">Laporan Penjualan</a></li>
                                @endcan
                                @can('report.pertahun.solditem')
                                <li class="{{ set_active('report/pertahun/solditem') }}"><a href="{{ url('/report/pertahun/solditem') }}">Laporan Sold Item</a></li>
                                <li class="{{ set_active('report/pertahun/solditembahan') }}"><a href="{{ url('/report/pertahun/solditembahan') }}">Laporan Sold Bahan</a></li>
                                @endcan
                                @can('report.pertahun.karyawan')
                                <li class="{{ set_active('report/pertahun/karyawan') }}"><a href="{{ url('/report/pertahun/karyawan') }}">Laporan Karyawan</a></li>
                                @endcan
                                @can('report.pertahun.labarugi')
                                <li class="{{ set_active('report/pertahun/labarugi') }}"><a href="{{ url('/report/pertahun/labarugi') }}">Laporan Laba/Rugi</a></li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </li>
                @endcan
                @can('account.read')
                <li class="{{ set_active('account*') }}">
                    <a href="javascript:;">
                        <i class="icon-star"></i>
                        <span class="title">Akun</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('account.create')
                        <li class="{{ set_active('account/add') }}">
                            <a href="{{ url('/account/add') }}">Tambah Akun</a>
                        </li>
                        @endcan
                        @can('account.read')
                        <li class="{{ set_active('account') }}">
                            <a href="{{ url('/account') }}">Daftar Akun</a>
                        </li>
                        @endcan
                        @can('account.saldo')
                        <li class="{{ set_active('account/saldo*') }}">
                            <a href="javascript:;">
                            Saldo Akun
                            <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @can('account.saldo.create')
                                <li class="{{ set_active('account/saldo/add') }}"><a href="{{ url('/account/saldo/add') }}">Input Saldo Akun</a></li>
                                @endcan
                                @can('account.saldo')
                                <li class="{{ set_active('account/saldo') }}"><a href="{{ url('/account/saldo') }}">Daftar Input Saldo Akun</a></li>
                                @endcan
                                <li class="{{ set_active('account/saldo/jurnal*') }}">
                                    <a href="javascript:;">
                                    Jurnal
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        @can('account.saldo.cash')
                                        <li class="{{ set_active('account/saldo/jurnal') }}"><a href="{{ url('/account/saldo/jurnal') }}">Jurnal Akun</a></li>
                                        @endcan
                                        @can('account.saldo.bank')
                                        <li class="{{ set_active('account/saldo/jurnal/bank') }}"><a href="{{ url('/account/saldo/jurnal/bank') }}">Jurnal Bank</a></li>
                                        @endcan
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('bank.read')
                <li class="{{ set_active('bank*') }}">
                    <a class="active" href="javascript:;">
                        <i class="icon-hdd"></i>
                        <span class="title">Bank</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('bank.create')
                        <li class="{{ set_active('bank/add') }}">
                            <a href="{{ url('/bank/add') }}">Tambah Bank</a>
                        </li>
                        @endcan
                        @can('bank.read')
                        <li class="{{ set_active('bank') }}">
                            <a href="{{ url('/bank') }}">Daftar Bank</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('produk.read')
                <li class="{{ set_active('produk*') }}">
                    <a href="javascript:;">
                        <i class="icon-certificate"></i>
                        <span class="title">Produk</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('produk_kategori.read')
                        <li class="{{ set_active('produk/kategori*') }}">
                            <a href="javascript:;">
                                Kategori Produk
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                @can('produk_kategori.create')
                                <li class="{{ set_active('produk/kategori/add') }}">
                                    <a href="{{ url('/produk/kategori/add') }}">Tambah Kategori Produk</a>
                                </li>
                                @endcan
                                @can('produk_kategori.read')
                                <li class="{{ set_active('produk/kategori') }}">
                                    <a href="{{ url('/produk/kategori') }}">Daftar Kategori Produk</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        @can('produk.create')
                        <li class="{{ set_active('produk/add') }}">
                            <a href="{{ url('/produk/add') }}">Tambah Produk</a>
                        </li>
                        @endcan
                        @can('produk.read')
                        <li class="{{ set_active('produk') }}">
                            <a href="{{ url('/produk') }}">Daftar Produk</a>
                        </li>
                        @endcan
                        @can('produk.stok')
                        <li class="{{ set_active('produk/stok') }}">
                            <a href="{{ url('/produk/stok') }}">Stok Produk</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('bahan.read')
                <li class="{{ set_active('bahan-produksi*') }}">
                    <a href="javascript:;">
                        <i class="icon-leaf"></i>
                        <span class="title">Bahan</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('bahan.create')
                        <li class="{{ set_active('bahan-produksi/add') }}">
                            <a href="{{ url('/bahan-produksi/add') }}">Tambah Bahan Produksi</a>
                        </li>
                        @endcan
                        @can('bahan.read')
                        <li class="{{ set_active('bahan-produksi') }}">
                            <a href="{{ url('/bahan-produksi') }}">Daftar Bahan Produksi</a>
                        </li>
                        @endcan
                        @can('bahan.stok')
                        <li class="{{ set_active('bahan-produksi/stok') }}">
                            <a href="{{ url('/bahan-produksi/stok') }}">Stok Bahan Produksi</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('customer.read')
                <li class="{{ set_active('customer*') }}">
                    <a class="active" href="javascript:;">
                        <i class="icon-user-md"></i>
                        <span class="title">Customer</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('customer.create')
                        <li class="{{ set_active('customer/add') }}">
                            <a href="{{ url('/customer/add') }}">Tambah Customer</a>
                        </li>
                        @endcan
                        @can('customer.read')
                        <li class="{{ set_active('customer') }}">
                            <a href="{{ url('/customer') }}">Daftar Customer</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('place.read')
                <li class="{{ set_active('place*') }}">
                    <a class="active" href="javascript:;">
                        <i class="icon-flag"></i>
                        <span class="title">Tempat</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('place_kategori.read')
                        <li class="{{ set_active('place/kategori*') }}">
                            <a href="javascript:;">
                                Kategori Tempat
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                @can('place_kategori.create')
                                <li class="{{ set_active('place/kategori/add') }}">
                                    <a href="{{ url('/place/kategori/add') }}">Tambah Kategori Tempat</a>
                                </li>
                                @endcan
                                @can('place_kategori.read')
                                <li class="{{ set_active('place/kategori') }}">
                                    <a href="{{ url('/place/kategori') }}">Daftar Kategori Tempat</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        @can('place.create')
                        <li class="{{ set_active('place/add') }}">
                            <a href="{{ url('/place/add') }}">Tambah Tempat</a>
                        </li>
                        @endcan
                        @can('place.read')
                        <li class="{{ set_active('place') }}">
                            <a href="{{ url('/place') }}">Daftar Tempat</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('karyawan.read')
                <li class="{{ set_active('karyawan*') }}">
                    <a class="active" href="javascript:;">
                        <i class="icon-user"></i>
                        <span class="title">Karyawan</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('karyawan.create')
                        <li class="{{ set_active('karyawan/add') }}">
                            <a href="{{ url('/karyawan/add') }}">Tambah Karyawan</a>
                        </li>
                        @endcan
                        @can('karyawan.read')
                        <li class="{{ set_active('karyawan') }}">
                            <a href="{{ url('/karyawan') }}">Daftar Karyawan</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('supplier.read')
                <li class="{{ set_active('supplier*') }}">
                    <a class="active" href="javascript:;">
                        <i class="icon-truck"></i>
                        <span class="title">Supplier</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('supplier.create')
                        <li class="{{ set_active('supplier/add') }}">
                            <a href="{{ url('/supplier/add') }}">Tambah Supplier</a>
                        </li>
                        @endcan
                        @can('supplier.read')
                        <li class="{{ set_active('supplier') }}">
                            <a href="{{ url('/supplier') }}">Daftar Supplier</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('tax.read')
                <li class="{{ set_active('tax*') }}">
                    <a class="active" href="javascript:;">
                        <i class="icon-credit-card"></i>
                        <span class="title">Pajak</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('tax.create')
                        <li class="{{ set_active('tax/add') }}">
                            <a href="{{ url('/tax/add') }}">Tambah Type Pajak</a>
                        </li>
                        @endcan
                        @can('tax.read')
                        <li class="{{ set_active('tax') }}">
                            <a href="{{ url('/tax') }}">Daftar Type Pajak</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('pembelian.read')
                <li class="{{ set_active('pembelian*') }}">
                    <a class="active" href="javascript:;">
                        <i class="icon-gift"></i>
                        <span class="title">Pembelian</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('pembelian.create')
                        <li class="{{ set_active('pembelian/add') }}">
                            <a href="{{ url('/pembelian/add') }}">Tambah Pembelian</a>
                        </li>
                        @endcan
                        @can('pembelian.read')
                        <li class="{{ set_active('pembelian') }}">
                            <a href="{{ url('/pembelian') }}">Daftar Pembelian</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('adjustment.read')
                <li class="{{ set_active('adjustment*') }}">
                    <a href="javascript:;">
                        <i class="icon-fire"></i>
                        <span class="title">Adjustment</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('adjustment.create')
                        <li class="{{ set_active('adjustment/add') }}">
                            <a href="{{ url('/adjustment/add') }}">Tambah Adjustment</a>
                        </li>
                        @endcan
                        @can('adjustment.read')
                        <li class="{{ set_active('adjustment') }}">
                            <a href="{{ url('/adjustment') }}">Daftar Adjustment</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('user.read')
                <li class="{{ set_active('user*') }}">
                    <a class="active" href="javascript:;">
                        <i class="icon-user"></i>
                        <span class="title">User Aplikasi</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('permission.read')
                        <li class="{{ set_active('user/permission*') }}">
                            <a href="javascript:;">
                                Permission
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                @can('permission.create')
                                <li class="{{ set_active('user/permission/add') }}">
                                    <a href="{{ url('/user/permission/add') }}">Tambah Permission</a>
                                </li>
                                @endcan
                                @can('permission.read')
                                <li class="{{ set_active('user/permission') }}">
                                    <a href="{{ url('/user/permission') }}">Daftar Permission</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        @can('userrole.read')
                        <li class="{{ set_active('user/role*') }}">
                            <a href="javascript:;">
                                Role
                                <span class="arrow "></span>
                            </a>
                            <ul class="sub-menu">
                                @can('userrole.create')
                                <li class="{{ set_active('user/role/add') }}">
                                    <a href="{{ url('/user/role/add') }}">Tambah Role</a>
                                </li>
                                @endcan
                                @can('userrole.read')
                                <li class="{{ set_active('user/role') }}">
                                    <a href="{{ url('/user/role') }}">Daftar Role</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        @can('user.create')
                        <li class="{{ set_active('user/add') }}">
                            <a href="{{ url('/user/add') }}">Tambah User</a>
                        </li>
                        @endcan
                        @can('user.read')
                        <li class="{{ set_active('user') }}">
                            <a href="{{ url('/user') }}">Daftar User</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                @can('setting.update')
                <li class="{{ set_active('setting*') }}">
                    <a href="{{ url('/setting') }}">
                        <i class="icon-cogs"></i>
                        <span class="title">Setting</span>
                    </a>
                </li>
                @endcan
            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN PAGE -->
        <div class="page-content">
            @yield('content')
        </div>
        <!-- END PAGE -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    <div class="footer">
        <div class="footer-inner">
            2013 &copy; Metronic by keenthemes.
        </div>
        <div class="footer-tools">
            <span class="go-top">
         <i class="icon-angle-up"></i>
         </span>
        </div>
    </div>
    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    <!-- BEGIN CORE PLUGINS -->
    <!--[if lt IE 9]>
   <script src="{{ url('/') }}/assets/metronic/plugins/respond.min.js"></script>
   <script src="{{ url('/') }}/assets/metronic/plugins/excanvas.min.js"></script>
   <![endif]-->
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/jquery.cookie.min.js" type="text/javascript"></script>
    <script src="{{ url('/') }}/assets/metronic/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ url('/') }}/assets/metronic/plugins/bootstrap-toastr/toastr.min.js"></script>
    @yield('js_assets')
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- END CORE PLUGINS -->
    <script src="{{ url('/') }}/assets/metronic/scripts/app.js"></script>
    <script>
        jQuery(document).ready(function() {
            // initiate layout and plugins
            App.init();

            toastr.options.closeButton = true;
            toastr.options.positionClass = "toast-bottom-right";
            @if(Session::has('succcess'))
            toastr.success('{{ Session::get("succcess") }}');
            @endif
            @if($errors->has('failed'))
            toastr.error('{{ $errors->first("failed") }}');
            @endif
        });
    </script>
    @yield('js_section')
</body>
<!-- END BODY -->

</html>
