<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function create(){
    	return view('Film/create');
    }
    //一
    public function dg(){
       static $i=20;

	   echo  $i.'<br>';
	   $i++;
	   if ($i<=10){
	      return $i;
		}
    }
}
