<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Session;

class LoginController extends Controller
{
    //
    function gen_uuid(){
        return $this->genUUID();
    }

    function page_login(){
    	return view('login');
    }	

    function submit_login(Request $r){
    	$username = $r->username;
    	$password = $r->password;

    	if (Auth::attempt(['username' => $username, 'password' => $password])) {
           	$this->generate_user_menu();
            return redirect()->intended('');
        }else{
           
        	return redirect('login')->with('error', 'Username dan Password Tidak Sesuai');
        }
    }

    function generate_user_menu(){
    	//ambil semua menu yang bisa diakses user
    	$id_user = Auth::user()->id;
    	$menu_user = array();
    
    	$menu_induk = DB::select("select d.*
							from user_role as a, role_menu as b, menu as c , menu as d
							where a.id_role = b.id_role and c.id_menu = b.id_menu  and c.id_menu_induk = d.id_menu
							and a.id_user = $id_user
							group by d.id_menu, d.nama_menu, d.url, d.id_menu_induk, d.urutan, d.icon, d.uuid order by d.urutan");

    	foreach($menu_induk as $mni){
    		$menu_user[$mni->id_menu]['id_menu'] = $mni->id_menu;
    		$menu_user[$mni->id_menu]['url'] = $mni->url;
    		$menu_user[$mni->id_menu]['icon'] = $mni->icon;
    		$menu_user[$mni->id_menu]['nama_menu'] = $mni->nama_menu;

    		$id_menu_induk = $mni->id_menu;

    		$menu_anak = DB::select("select c.nama_menu, c.id_menu, c.url, c.urutan, c.id_menu_induk from 
                user_role as a, role_menu as b, menu as c , menu as d 
                where a.id_role = b.id_role and c.id_menu = b.id_menu and c.id_menu_induk = d.id_menu and a.id_user = $id_user and c.id_menu_induk=$id_menu_induk group by c.nama_menu, c.id_menu, c.url, c.urutan, c.id_menu_induk
                order by c.id_menu_induk, c.urutan ");

    		$temp_anak = array();
    		foreach($menu_anak as $mna){
    			array_push($temp_anak, array("id_menu"=>$mna->id_menu, "url"=>$mna->url, "nama_menu"=>$mna->nama_menu));
    		}
    		$menu_user[$mni->id_menu]['child'] = $temp_anak;
    	}	
        $menu_user = json_encode($menu_user);
        Session::put('menusesuax',$menu_user); 	
    }

    function logout(){
    	Auth::logout();
        Session::flush();
        return redirect('/');
    }
}
