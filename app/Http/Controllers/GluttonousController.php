<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GluttonousController extends Controller
{
    function index(){
    	return view('Gluttonous.gluttonous');
    }
}
