<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Uuid;
use Session;

class HomeController extends Controller
{
    //

    function beranda(){
    	return view('home.index');
    }
}
