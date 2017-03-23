<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Excel;
use Log;

class ExcelController extends Controller
{
    public function index()
    {
        $data = Excel::selectSheetsByIndex(0)->load(storage_path().'/excel/sample.xlsx')->get();

        return $data;
    }
}
