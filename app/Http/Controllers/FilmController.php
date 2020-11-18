<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function create(){
    	return view('Film/create');
    }
    //
    public function dg(){
       static $i=20;

	   echo  $i.'<br>';
	   $i++;
	   if ($i<=10){
	      return $i;
		}
    }
    //盒子div 轮播图模拟
    public function home(){
        return view('home');
    }
}
