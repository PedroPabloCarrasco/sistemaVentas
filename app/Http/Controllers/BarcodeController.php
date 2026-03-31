<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function index()
    {
        return view('barcode.index'); // apunta a resources/views/barcode/index.blade.php
    }
}
