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

    <link rel="shortcut icon" href="{{ url('/') }}/assets/logo.ico" />
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
                <img src="{{ url('/') }}/assets/logotext.png" alt="logo" class="img-responsive" />
            </a>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <img src="{{ url('/') }}/assets/metronic/img/menu-toggler.png" alt="" />
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <ul class="nav navbar-nav pull-right">
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
                                <li class="{{ set_active('report/pertanggal/solditem*') }}">
                                    <a href="javascript:;">
                                    Sold Item
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/pertanggal/solditem/produk') }}"><a href="{{ url('/report/pertanggal/solditem/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/pertanggal/solditem/bahan') }}"><a href="{{ url('/report/pertanggal/solditem/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
                                @endcan
                                @can('report.pertanggal.purchaseditem')
                                <li class="{{ set_active('report/pertanggal/purchaseditem*') }}">
                                    <a href="javascript:;">
                                    Purchased Item
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/pertanggal/purchaseditem/produk') }}"><a href="{{ url('/report/pertanggal/purchaseditem/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/pertanggal/purchaseditem/bahan') }}"><a href="{{ url('/report/pertanggal/purchaseditem/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
                                @endcan
                                @can('report.pertanggal.adjustment')
                                <li class="{{ set_active('report/pertanggal/adjustment') }}"><a href="{{ url('/report/pertanggal/adjustment') }}">Laporan Adjustment</a></li>
                                @endcan
                                @can('report.pertanggal.stok')
                                <li class="{{ set_active('report/pertanggal/stok*') }}">
                                    <a href="javascript:;">
                                    Mutasi Stok
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/pertanggal/stok/produk') }}"><a href="{{ url('/report/pertanggal/stok/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/pertanggal/stok/bahan') }}"><a href="{{ url('/report/pertanggal/stok/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
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
                                <li class="{{ set_active('report/periode/solditem*') }}">
                                    <a href="javascript:;">
                                    Sold Item
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/periode/solditem/produk') }}"><a href="{{ url('/report/periode/solditem/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/periode/solditem/bahan') }}"><a href="{{ url('/report/periode/solditem/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
                                @endcan
                                @can('report.periode.purchaseditem')
                                <li class="{{ set_active('report/periode/purchaseditem*') }}">
                                    <a href="javascript:;">
                                    Purchased Item
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/periode/purchaseditem/produk') }}"><a href="{{ url('/report/periode/purchaseditem/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/periode/purchaseditem/bahan') }}"><a href="{{ url('/report/periode/purchaseditem/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
                                @endcan
                                @can('report.periode.adjustment')
                                <li class="{{ set_active('report/periode/adjustment') }}"><a href="{{ url('/report/periode/adjustment') }}">Laporan Adjustment</a></li>
                                @endcan
                                @can('report.periode.stok')
                                <li class="{{ set_active('report/periode/stok*') }}">
                                    <a href="javascript:;">
                                    Mutasi Stok
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/periode/stok/produk') }}"><a href="{{ url('/report/periode/stok/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/periode/stok/bahan') }}"><a href="{{ url('/report/periode/stok/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
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
                                <li class="{{ set_active('report/perbulan/solditem*') }}">
                                    <a href="javascript:;">
                                    Sold Item
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/perbulan/solditem/produk') }}"><a href="{{ url('/report/perbulan/solditem/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/perbulan/solditem/bahan') }}"><a href="{{ url('/report/perbulan/solditem/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
                                @endcan
                                @can('report.perbulan.purchaseditem')
                                <li class="{{ set_active('report/perbulan/purchaseditem*') }}">
                                    <a href="javascript:;">
                                    Purchased Item
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/perbulan/purchaseditem/produk') }}"><a href="{{ url('/report/perbulan/purchaseditem/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/perbulan/purchaseditem/bahan') }}"><a href="{{ url('/report/perbulan/purchaseditem/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
                                @endcan
                                @can('report.perbulan.adjustment')
                                <li class="{{ set_active('report/perbulan/adjustment') }}"><a href="{{ url('/report/perbulan/adjustment') }}">Laporan Adjustment</a></li>
                                @endcan
                                @can('report.perbulan.stok')
                                <li class="{{ set_active('report/perbulan/stok*') }}">
                                    <a href="javascript:;">
                                    Mutasi Stok
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/perbulan/stok/produk') }}"><a href="{{ url('/report/perbulan/stok/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/perbulan/stok/bahan') }}"><a href="{{ url('/report/perbulan/stok/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
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
                                <li class="{{ set_active('report/pertahun/solditem*') }}">
                                    <a href="javascript:;">
                                    Sold Item
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/pertahun/solditem/produk') }}"><a href="{{ url('/report/pertahun/solditem/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/pertahun/solditem/bahan') }}"><a href="{{ url('/report/pertahun/solditem/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
                                @endcan
                                @can('report.pertahun.purchaseditem')
                                <li class="{{ set_active('report/pertahun/purchaseditem*') }}">
                                    <a href="javascript:;">
                                    Purchased Item
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/pertahun/purchaseditem/produk') }}"><a href="{{ url('/report/pertahun/purchaseditem/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/pertahun/purchaseditem/bahan') }}"><a href="{{ url('/report/pertahun/purchaseditem/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
                                @endcan
                                @can('report.pertahun.adjustment')
                                <li class="{{ set_active('report/pertahun/adjustment') }}"><a href="{{ url('/report/pertahun/adjustment') }}">Laporan Adjustment</a></li>
                                @endcan
                                @can('report.pertahun.stok')
                                <li class="{{ set_active('report/pertahun/stok*') }}">
                                    <a href="javascript:;">
                                    Mutasi Stok
                                    <span class="arrow"></span>
                                    </a>
                                    <ul class="sub-menu">
                                        <li class="{{ set_active('report/pertahun/stok/produk') }}"><a href="{{ url('/report/pertahun/stok/produk') }}">Produk</a></li>
                                        <li class="{{ set_active('report/pertahun/stok/bahan') }}"><a href="{{ url('/report/pertahun/stok/bahan') }}">Bahan</a></li>
                                    </ul>
                                </li>
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
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub-menu">
                        @can('setting.update')
                        <li class="{{ set_active('setting') }}">
                            <a href="{{ url('/setting') }}">General Setting</a>
                        </li>
                        @endcan
                        @can('app.reset')
                        <li class="{{ set_active('setting/reset') }}">
                            <a href="{{ url('/setting/reset') }}">Reset Aplikasi</a>
                        </li>
                        @endcan
                    </ul>
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
            2016 &copy; Pondok Indah. Resto Application by <a href="https://www.facebook.com/Ahmad.Rizal.Afani">Ahmad Rizal Afani</a>.
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
