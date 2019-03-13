<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Datatables;
use Crypt;

class SettingController extends Controller
{
    //

    function setting_menu(){
    	$pagetitle = "Setting Menu";
    	$smalltitle = "Pengaturan Menu Aplikasi";
    	return view('setting.menu', compact('pagetitle','smalltitle'));
    }

    function datatable_menu(Request $r){
        
        $filter = "";
        if (request()->has('search')) {
            $search = request('search');
            $keyword = $search['value'];
            if(strlen($keyword)>=3){
                $keyword = strtolower($keyword);
                $filter = " and (lower(a.nama_menu) like '%$keyword%' or lower(b.nama_menu) like '%$keyword%') ";
            }   
        }

         $sql_union = "select a.nama_menu , a.url, a.uuid, a.urutan, b.nama_menu as group_menu from menu as a, menu as b where a.id_menu_induk = b.id_menu and a.id_menu_induk > 0  $filter order by a.id_menu_induk  ";

         $query = DB::table(DB::raw("($sql_union) as x"))
                    ->select(['group_menu','nama_menu', 'url','uuid','urutan']);

         return Datatables::of($query)
            ->addColumn('action', function ($query) {
                    $edit = ""; $delete = "";
                    if($this->ucu()){
                        $edit = '<a href="#" class="act green" data-toggle="modal" data-uuid="'.$query->uuid.'"data-target="#modal-edit-menu" title="Edit"><i class="la la-edit"></i></a> ';
                    }
                    if($this->ucd()){
                        $delete = '<a href="#" data-target="#modal-hapus-menu" data-uuid="'.$query->uuid.'"  title="Hapus" data-toggle="modal" class="act red"><i class="la la-times"></i></a> ';
                    }
                    $action =  $edit."".$delete;
                    if ($action==""){$action='<a href="#" class="act"><i class="la la-lock"></i></a>'; }
                        return $action;
            })
            ->addIndexColumn()
            ->rawColumns(['action','label'])
            ->make(true);
    }

    

    function get_data_menu($uuid){
    	$menu = DB::table('menu')->where('uuid', $uuid)->first();
        if($menu){
            $respon = array('status'=>true,'data'=>$menu, 
            	'informasi'=>'Nama Menu: '. $menu->nama_menu);
            return response()->json($respon);
        }
        $respon = array('status'=>false,'message'=>'Data Tidak Ditemukan');
        return response()->json($respon);
    }

    function submit_insert_menu(Request $r){
    	if($this->ucc()){
	    	loadHelper('format');
	    	$uuid = $this->genUUID();

	    	$record = array(
	    		"id_menu_induk"=>$r->id_menu_induk, 
	    		"nama_menu"=>trim($r->nama_menu), 
	    		"url"=>trim($r->url),
	    		"urutan"=>$r->urutan, 
	    		"uuid"=>$uuid);

	    	DB::table('menu')->insert($record);
	    	$respon = array('status'=>true,'message'=>'Menu Berhasil Ditambahkan!', '_token'=>csrf_token());
        	return response()->json($respon);
    	}else{
    		$respon = array('status'=>false,'message'=>'Akses Ditolak!');
        	return response()->json($respon);
    	}
    }

    function submit_update_menu(Request $r){
    	if($this->ucu()){
	    	loadHelper('format');
	    	$uuid = $r->uuid;

	    	$record = array(
	    		"id_menu_induk"=>$r->id_menu_induk, 
	    		"nama_menu"=>trim($r->nama_menu), 
	    		"url"=>trim($r->url),
	    		"urutan"=>$r->urutan
	    	);

	    	DB::table('menu')->where('uuid', $uuid)->update($record);
	    	$respon = array('status'=>true,'message'=>'Data Menu Berhasil Disimpan!', 
	    		'_token'=>csrf_token());
        	return response()->json($respon);
    	}else{
    		$respon = array('status'=>false,'message'=>'Akses Ditolak!');
        	return response()->json($respon);
    	}
    }

}
