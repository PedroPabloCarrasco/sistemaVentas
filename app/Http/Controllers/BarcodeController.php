<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    // Método que sirve para mostrar la vista
    public function index()
    {
        return view('barcode.index'); // Asegúrate de que esta vista exista
    }
}
