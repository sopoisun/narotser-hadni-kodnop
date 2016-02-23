<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/', function () {
        if( auth()->check() ){
            return app('App\Http\Controllers\DashboardController')->index();
        }else{
            return app('App\Http\Controllers\Auth\AuthController')->showLoginForm();
        }
        //return view('welcome');
    });

    Route::auth();

    Route::group(['middleware' => ['auth']], function (){
        // Dashboard
        Route::get('/dashboard', 'DashboardController@index');

        // Karyawan
        Route::get('/karyawan', 'KaryawanController@index');
        Route::get('/karyawan/add', 'KaryawanController@create');
        Route::post('/karyawan/add', 'KaryawanController@store');
        Route::get('/karyawan/edit/{id}', 'KaryawanController@edit');
        Route::post('/karyawan/edit/{id}', 'KaryawanController@update');
        Route::get('/karyawan/delete/{id}', 'KaryawanController@destroy');
        Route::get('/karyawan/report/perbulan', 'KaryawanController@reportPerbulan');
        Route::get('/karyawan/report/perbulan-print', 'KaryawanController@reportPerbulanPrint');
        Route::get('/karyawan/report/pertahun', 'KaryawanController@reportPertahun');
        Route::get('/karyawan/report/pertahun-print', 'KaryawanController@reportPertahunPrint');

        // Place Kategori
        Route::get('/place/kategori', 'PlaceKategoriController@index');
        Route::get('/place/kategori/add', 'PlaceKategoriController@create');
        Route::post('/place/kategori/add', 'PlaceKategoriController@store');
        Route::get('/place/kategori/edit/{id}', 'PlaceKategoriController@edit');
        Route::post('/place/kategori/edit/{id}', 'PlaceKategoriController@update');
        Route::get('/place/kategori/delete/{id}', 'PlaceKategoriController@destroy');

        // Place
        Route::get('/place', 'PlaceController@index');
        Route::get('/place/add', 'PlaceController@create');
        Route::post('/place/add', 'PlaceController@store');
        Route::get('/place/edit/{id}', 'PlaceController@edit');
        Route::post('/place/edit/{id}', 'PlaceController@update');
        Route::get('/place/delete/{id}', 'PlaceController@destroy');

        // Supplier
        Route::get('/supplier', 'SupplierController@index');
        Route::get('/supplier/add', 'SupplierController@create');
        Route::post('/supplier/add', 'SupplierController@store');
        Route::get('/supplier/edit/{id}', 'SupplierController@edit');
        Route::post('/supplier/edit/{id}', 'SupplierController@update');
        Route::get('/supplier/delete/{id}', 'SupplierController@destroy');

        // Tax ( Pajak )
        Route::get('/tax', 'TaxController@index');
        Route::get('/tax/add', 'TaxController@create');
        Route::post('/tax/add', 'TaxController@store');
        Route::get('/tax/edit/{id}', 'TaxController@edit');
        Route::post('/tax/edit/{id}', 'TaxController@update');

        // Customer
        Route::get('/customer', 'CustomerController@index');
        Route::get('/customer/add', 'CustomerController@create');
        Route::post('/customer/add', 'CustomerController@store');
        Route::get('/customer/edit/{id}', 'CustomerController@edit');
        Route::post('/customer/edit/{id}', 'CustomerController@update');
        Route::get('/customer/delete/{id}', 'CustomerController@destroy');

        // Bahan
        Route::get('/bahan-produksi', 'BahanController@index');
        Route::get('/bahan-produksi/stok', 'BahanController@stok');
        Route::get('/bahan-produksi/add', 'BahanController@create');
        Route::post('/bahan-produksi/add', 'BahanController@store');
        Route::get('/bahan-produksi/edit/{id}', 'BahanController@edit');
        Route::post('/bahan-produksi/edit/{id}', 'BahanController@update');
        Route::get('/bahan-produksi/delete/{id}', 'BahanController@destroy');

        // Produk Kategori
        Route::get('/produk/kategori', 'ProdukKategoriController@index');
        Route::get('/produk/kategori/add', 'ProdukKategoriController@create');
        Route::post('/produk/kategori/add', 'ProdukKategoriController@store');
        Route::get('/produk/kategori/edit/{id}', 'ProdukKategoriController@edit');
        Route::post('/produk/kategori/edit/{id}', 'ProdukKategoriController@update');
        Route::get('/produk/kategori/delete/{id}', 'ProdukKategoriController@destroy');

        // Produk
        Route::get('/produk', 'ProdukController@index');
        Route::get('/produk/stok', 'ProdukController@stok');
        Route::get('/produk/add', 'ProdukController@create');
        Route::post('/produk/add', 'ProdukController@store');
        Route::get('/produk/edit/{id}', 'ProdukController@edit');
        Route::post('/produk/edit/{id}', 'ProdukController@update');
        Route::get('/produk/delete/{id}', 'ProdukController@destroy');

        // Pembelian
        Route::get('/pembelian', 'PembelianController@index');
        Route::get('/pembelian/add', 'PembelianController@create');
        Route::post('/pembelian/add', 'PembelianController@store');
        Route::get('/pembelian/detail/{id}', 'PembelianController@detail');
        Route::get('/pembelian/bayar/{id}', 'PembelianController@bayar');
        Route::post('/pembelian/bayar/{id}', 'PembelianController@bayarStore');
        Route::get('/pembelian/show', 'PembelianController@showItem');

        // Adjustment
        Route::get('/adjustment', 'AdjustmentController@index');
        Route::get('/adjustment/add', 'AdjustmentController@create');
        Route::post('/adjustment/add', 'AdjustmentController@store');
        Route::get('/adjustment/detail/{id}', 'AdjustmentController@detail');
        Route::get('/adjustment/show', 'AdjustmentController@showSession');
        Route::get('/adjustment/test', 'AdjustmentController@showTest');

        // Bank
        Route::get('/bank', 'BankController@index');
        Route::get('/bank/add', 'BankController@create');
        Route::post('/bank/add', 'BankController@store');
        Route::get('/bank/edit/{id}', 'BankController@edit');
        Route::post('/bank/edit/{id}', 'BankController@update');
        Route::get('/bank/delete/{id}', 'BankController@destroy');

        // Setting
        Route::get('/setting', 'SettingController@index');
        Route::post('/setting', 'SettingController@save');

        // Order
        Route::get('/order', 'OrderController@index');
        Route::get('/order/pertanggal', 'OrderController@pertanggal');
        Route::get('/order/pertanggal/detail', 'OrderController@pertanggalDetail');
        Route::post('/order/pertanggal/detail', 'OrderController@savePertanggalDetail');
        Route::get('/order/pertanggal/return', 'OrderController@pertanggalReturn');
        Route::get('/order/pertanggal/return/detail', 'OrderController@pertanggalReturnDetail');
        # Show Stored New Data Detail
        Route::get('/order/produk', 'OrderController@showProduk');
        # Show Stored New Remove Data Detail
        Route::get('/order/produk/remove', 'OrderController@removeDetailProdukSessionShow');
        # Open Order
        Route::post('/order/open/save', 'OrderController@saveOpenOrder');
        Route::get('/order/{id}/open', 'OrderController@openOrder'); // place place_id
        # Change Order
        Route::get('/order/{id}/rechange', 'OrderController@reChangeOrder');
        Route::get('/order/{id}/change', 'OrderController@changeOrder'); // by order_id
        Route::post('/order/{id}/change', 'OrderController@saveChangeOrder'); // by order_id
        # Close Order
        Route::get('/order/{id}/close', 'OrderController@closeOrder'); // by order_id
        Route::post('/order/{id}/close', 'OrderController@saveCloseOrder'); // by order_id

        // Reporting
        Route::get('/report', 'ReportController@index');
        # Pertanggal
        Route::get('/report/pertanggal', 'ReportController@pertanggal');
        Route::get('/report/pertanggal-print', 'ReportController@pertanggalPrint');
        Route::get('/report/pertanggal/solditem', 'ReportController@soldItem');
        Route::get('/report/pertanggal/solditem-print', 'ReportController@soldItemPrint');
        Route::get('/report/pertanggal/solditembahan', 'ReportController@soldItemBahan');
        Route::get('/report/pertanggal/solditembahan-print', 'ReportController@soldItemBahanPrint');
        Route::get('/report/pertanggal/karyawan', 'ReportController@karyawan');
        Route::get('/report/pertanggal/karyawan-print', 'ReportController@karyawanPrint');
        Route::get('/report/pertanggal/karyawan/detail', 'ReportController@karyawanDetail');
        Route::get('/report/pertanggal/detail/{id}', 'ReportController@detail');
        Route::get('/report/pertanggal/labarugi', 'ReportController@labaRugi');
        Route::get('/report/pertanggal/labarugi-print', 'ReportController@labaRugiPrint');
        # Periode
        Route::get('/report/periode/solditem', 'ReportController@soldItemPeriode');
        Route::get('/report/periode/solditem-print', 'ReportController@soldItemPeriodePrint');
        Route::get('/report/periode/solditembahan', 'ReportController@soldItemBahanPeriode');
        Route::get('/report/periode/solditembahan-print', 'ReportController@soldItemBahanPeriodePrint');
        Route::get('/report/periode/karyawan', 'ReportController@karyawanPeriode');
        Route::get('/report/periode/karyawan-print', 'ReportController@karyawanPeriodePrint');
        Route::get('/report/periode/labarugi', 'ReportController@labaRugiPeriode');
        Route::get('/report/periode/labarugi-print', 'ReportController@labaRugiPeriodePrint');
        # Perbulan
        Route::get('/report/perbulan', 'ReportController@perbulan');
        Route::get('/report/perbulan-print', 'ReportController@perbulanPrint');
        Route::get('/report/perbulan/solditem', 'ReportController@soldItemPerbulan');
        Route::get('/report/perbulan/solditem-print', 'ReportController@soldItemPerbulanPrint');
        Route::get('/report/perbulan/solditembahan', 'ReportController@soldItemBahanPerbulan');
        Route::get('/report/perbulan/solditembahan-print', 'ReportController@soldItemBahanPerbulanPrint');
        Route::get('/report/perbulan/karyawan', 'ReportController@karyawanPerbulan');
        Route::get('/report/perbulan/karyawan-print', 'ReportController@karyawanPerbulanPrint');
        Route::get('/report/perbulan/labarugi', 'ReportController@labaRugiPerbulan');
        Route::get('/report/perbulan/labarugi-print', 'ReportController@labaRugiPerbulanPrint');
        # Pertahun
        Route::get('/report/pertahun', 'ReportController@pertahun');
        Route::get('/report/pertahun-print', 'ReportController@pertahunPrint');
        Route::get('/report/pertahun/solditem', 'ReportController@soldItemPertahun');
        Route::get('/report/pertahun/solditem-print', 'ReportController@soldItemPertahunPrint');
        Route::get('/report/pertahun/solditembahan', 'ReportController@soldItemBahanPertahun');
        Route::get('/report/pertahun/solditembahan-print', 'ReportController@soldItemBahanPertahunPrint');
        Route::get('/report/pertahun/karyawan', 'ReportController@karyawanPertahun');
        Route::get('/report/pertahun/karyawan-print', 'ReportController@karyawanPertahunPrint');
        Route::get('/report/pertahun/labarugi', 'ReportController@labaRugiPertahun');
        Route::get('/report/pertahun/labarugi-print', 'ReportController@labaRugiPertahunPrint');

        // Account
        Route::get('/account', 'AccountController@index');
        Route::get('/account/add', 'AccountController@create');
        Route::post('/account/add', 'AccountController@store');
        Route::get('/account/edit/{id}', 'AccountController@edit');
        Route::post('/account/edit/{id}', 'AccountController@update');
        # Saldo Akun
        Route::get('/account/saldo', 'AccountController@inputSaldo');
        # Input Manual
        Route::get('/account/saldo/add', 'AccountController@inputManual');
        Route::post('/account/saldo/add', 'AccountController@saveInputManual');
        Route::get('/account/saldo/edit/{id}', 'AccountController@editInputManual');
        Route::post('/account/saldo/edit/{id}', 'AccountController@saveEditInputManual');
        # Jurnal Harian
        Route::get('/account/saldo/jurnal', 'AccountController@jurnal');
        Route::get('/account/saldo/jurnal-print', 'AccountController@jurnalPrint');
        Route::get('/account/saldo/jurnal/bank', 'AccountController@jurnalBank');
        Route::get('/account/saldo/jurnal/bank-print', 'AccountController@jurnalBankPrint');

        // User
        Route::get('/user', 'UserController@index');
        Route::get('/user/add', 'UserController@create');
        Route::post('/user/add', 'UserController@store');
        Route::get('/user/edit/{id}', 'UserController@edit');
        Route::post('/user/edit/{id}', 'UserController@update');
        Route::get('/user/delete/{id}', 'UserController@destroy');
        Route::get('/change-password', 'UserController@changePassword');
        Route::post('/change-password', 'UserController@saveChangePassword');

        // Permission
        Route::get('/user/permission', 'PermissionController@index');
        Route::get('/user/permission/add', 'PermissionController@create');
        Route::post('/user/permission/add', 'PermissionController@store');
        Route::get('/user/permission/edit/{id}', 'PermissionController@edit');
        Route::post('/user/permission/edit/{id}', 'PermissionController@update');
        Route::get('/user/permission/delete/{id}', 'PermissionController@destroy');

        // Role
        Route::get('/user/role', 'RoleController@index');
        Route::get('/user/role/add', 'RoleController@create');
        Route::post('/user/role/add', 'RoleController@store');
        Route::get('/user/role/edit/{id}', 'RoleController@edit');
        Route::post('/user/role/edit/{id}', 'RoleController@update');
        Route::get('/user/role/delete/{id}', 'RoleController@destroy');

        Route::group(['prefix' => 'ajax'], function(){
            // Karyawan
            Route::get('/karyawan', 'KaryawanController@ajaxLoad');
            // Supplier
            Route::get('/supplier', 'SupplierController@ajaxLoad');
            // Customer
            Route::get('/customer', 'CustomerController@ajaxLoad');
            // Tax
            Route::get('tax', 'TaxController@ajaxLoad');
            // Bank
            Route::get('bank', 'BankController@ajaxLoad');
            // Bahan
            Route::get('/bahan', 'BahanController@ajaxLoad');
            // Produk
            Route::get('/produk', 'ProdukController@ajaxLoad');
            // Pembelian
            Route::post('/pembelian/item/save', 'PembelianController@saveItem');
            Route::get('/pembelian/item/remove', 'PembelianController@removeItem');
            // Adjustment
            Route::post('/adjustment/item_save', 'AdjustmentController@itemSave');
            Route::get('/adjustment/item_remove', 'AdjustmentController@itemRemove');

            // Order
            # Search Place
            Route::get('/place', 'OrderController@getPlace');
            # Search Produk
            Route::get('/order/produk', 'OrderController@getProduk'); // search
            # Order Detail Return
            Route::get('/order/detail/return', 'OrderController@pertanggalDetailReturn');
            Route::post('/order/detail/return', 'OrderController@savePertanggalDetailReturn');
            # Store new data detail session
            Route::post('/order/produk/save', 'OrderController@saveProduk'); // store session
            Route::get('/order/produk/remove', 'OrderController@removeProduk'); // remove store session
            # Store new remove data detail session
            Route::post('/order/detail/remove', 'OrderController@removeDetailProdukSession');
            # Cancel Order
            Route::get('/order/{id}/cancel', 'OrderController@cancelOrder'); // by order_id
            Route::post('/order/{id}/cancel', 'OrderController@saveCancelOrder'); // by order_id
            # Merge Order
            Route::get('/order/{id}/merge', 'OrderController@mergeOrder'); // by order_id
            Route::post('/order/{id}/merge', 'OrderController@saveMergeOrder'); // by order_id

            // Account
            Route::get('/account/check', 'AccountController@check');
        });
    });
});

Route::group(['prefix' => 'api', 'namespace' => 'Api'], function(){
    Route::post('/login', 'ApiController@index');

    Route::group(['middleware' => ['auth.api']], function(){
        Route::get('/user', 'ApiController@user');
        Route::get('/karyawan', 'ApiController@karyawan');
        Route::get('/bank', 'ApiController@bank');
        Route::get('/tax', 'ApiController@tax');
        Route::get('/customer', 'ApiController@customer');
        Route::get('/produk', 'ApiController@produk');
        Route::get('/produk/stok', 'ApiController@checkStok');
        Route::get('/place', 'ApiController@place');
        Route::get('/setting', 'ApiController@setting');
        Route::get('/transaksi', 'ApiController@transaksi');
        Route::post('/transaksi/save', 'ApiController@OpenTransaksi');
        Route::post('/transaksi/change', 'ApiController@changeTransaksi');
        Route::get('/transaksi/detail', 'ApiController@detail');
        Route::get('/transaksi/bayar', 'ApiController@bayar');
    });
});
