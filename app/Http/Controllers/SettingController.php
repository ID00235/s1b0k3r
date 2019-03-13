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

    //######################### SETTING ROLE #####################################
    function setting_role(){
        $pagetitle = "Setting Role";
        $smalltitle = "Pengaturan Role User Aplikasi";
        return view('setting.role', compact('pagetitle','smalltitle'));
    }

    function datatable_role(Request $r){
        
        $filter = "";
        if (request()->has('search')) {
            $search = request('search');
            $keyword = $search['value'];
            if(strlen($keyword)>=3){
                $keyword = strtolower($keyword);
                $filter = " and (lower(a.nama_role) like '%$keyword%') ";
            }   
        }

         $sql_union = "SELECT a.uuid, a.id_role, a.nama_role, count(b.id_menu) as menu_role
                        FROM role AS a LEFT JOIN role_menu AS b ON a.id_role = b.id_role where a.id_role != 0  
                        group by a.uuid, a.id_role, a.nama_role order by a.id_role asc ";

         $query = DB::table(DB::raw("($sql_union) as x"))
                    ->select(['uuid','nama_role', 'menu_role']);

         return Datatables::of($query)
            ->addColumn('action', function ($query) {
                $edit = ""; $delete = "";
                if($this->ucu()){
                    $edit = '<a href="#" class="act green" data-toggle="modal" data-uuid="'.$query->uuid.'"data-target="#modal-edit-role" title="Edit"><i class="la la-edit"></i></a> ';
                }
                if($this->ucd()){
                    $delete = '<a href="#" data-target="#modal-hapus-role" data-uuid="'.$query->uuid.'"  title="Hapus" data-toggle="modal" class="act red"><i class="la la-trash"></i></a> ';
                }
                $action =  $edit."".$delete;
                if ($action==""){$action='<a href="#" class="act"><i class="la la-lock"></i></a>'; }
                    return $action;
            })
            ->editColumn('menu_role', function($query){
                $action='<a href="'.url('setting-role/menu/'.$query->uuid).'" class="btn btn-sm btn-default"><i class="la la-list"></i> '.$query->menu_role.' Menu</a>';
                return $action;
            })
            ->addIndexColumn()
            ->rawColumns(['action','menu_role'])
            ->make(true);
    }

    

    function get_data_role($uuid){
        $menu = DB::table('role')->where('uuid', $uuid)->first();
        if($menu){
            $respon = array('status'=>true,'data'=>$menu, 
                'informasi'=>'Nama Role: '. $menu->nama_role);
            return response()->json($respon);
        }
        $respon = array('status'=>false,'message'=>'Data Tidak Ditemukan');
        return response()->json($respon);
    }

    function submit_insert_role(Request $r){
        if($this->ucc()){
            loadHelper('format');
            $uuid = $this->genUUID();

            $record = array(
                "nama_role"=>trim($r->nama_role), 
                "uuid"=>$uuid);

            DB::table('role')->insert($record);
            $respon = array('status'=>true,'message'=>'Role Berhasil Ditambahkan!', '_token'=>csrf_token());
            return response()->json($respon);
        }else{
            $respon = array('status'=>false,'message'=>'Akses Ditolak!');
            return response()->json($respon);
        }
    }

    function submit_update_role(Request $r){
        if($this->ucu()){
            loadHelper('format');
            $uuid = $r->uuid;

            $record = array(
                "nama_role"=>trim($r->nama_role)
            );

            DB::table('role')->where('uuid', $uuid)->update($record);
            $respon = array('status'=>true,'message'=>'Data Role Berhasil Disimpan!', 
                '_token'=>csrf_token());
            return response()->json($respon);
        }else{
            $respon = array('status'=>false,'message'=>'Akses Ditolak!');
            return response()->json($respon);
        }
    }

    function submit_delete_role(Request $r){
        if($this->ucd()){
            loadHelper('format');
            $uuid = $r->uuid;
            $data = DB::table('role')->where("uuid", $uuid)->first();
            
            $exist_role_menu = DB::table('role_menu')->where('id_role', $data->id_role)->count();
            if($exist_role_menu==0){
                DB::table('role')->where('uuid', $uuid)->delete();
                $respon = array('status'=>true,'message'=>'Data Role Berhasil Dihapus!', 
                '_token'=>csrf_token());
            }else{
                $respon = array('status'=>false,'message'=>'Data Role Tidak Dihapus!', 
                '_token'=>csrf_token());
            }            
            return response()->json($respon);
        }else{
            $respon = array('status'=>false,'message'=>'Akses Ditolak!');
            return response()->json($respon);
        }
    }
    //#######################################################################################


    function get_menu_role($uuid){
        $role = DB::table('role')->where('uuid', $uuid)->first();
        $id_role = $role->id_role;
        $uuid_role  = $uuid;
        $pagetitle = "Setting Menu - Role ";
        $smalltitle = "Pengaturan Menu Role : ". $role->nama_role;
        return view('setting.menu-role', compact('pagetitle', 'smalltitle', 
            'uuid_role', 'role'));
    }

    function datatable_menu_role($uuid){
        $role = DB::table('role')->where('uuid', $uuid)->first();
        $id_role = $role->id_role;

        $filter = "";
        if (request()->has('search')) {
            $search = request('search');
            $keyword = $search['value'];
            if(strlen($keyword)>=3){
                $keyword = strtolower($keyword);
                $filter = " and (lower(b.nama_menu) like '%$keyword%') ";
            }   
        }

         $sql_union = "select b.nama_menu, a.uuid, a.ucu, a.ucd, a.ucc
                            from role_menu as a, menu as b where 
                            a.id_menu = b.id_menu and a.id_role = $id_role $filter ";

         $query = DB::table(DB::raw("($sql_union) as x"))
                    ->select(['uuid','nama_menu', 'ucc', 'ucd', 'ucu']);

         return Datatables::of($query)
            ->addColumn('action', function ($query) {
                $edit = ""; $delete = "";
                if($this->ucu()){
                    $edit = '<a href="#" data-target="#modal-edit-menu-role" data-uuid="'.$query->uuid.'"  title="Hapus" data-toggle="modal" class="act green"><i class="la la-edit"></i></a> ';
                }
                if($this->ucd()){
                    $delete = '<a href="#" data-target="#modal-hapus-menu-role" data-uuid="'.$query->uuid.'"  title="Hapus" data-toggle="modal" class="act red"><i class="la la-trash"></i></a> ';
                }
                $action =  $edit."".$delete;
                if ($action==""){$action='<a href="#" class="act"><i class="la la-lock"></i></a>'; }
                    return $action;
            })
            ->addIndexColumn()
            ->rawColumns(['action','menu_role'])
            ->make(true);
    }

    function get_data_role_menu($uuid){
        $data = DB::select("select a.nama_menu, b.* from menu as a, role_menu as b where a.id_menu = b.id_menu and b.uuid ='$uuid' ");

        if(count($data)==1){
            $data = $data[0];
            $respon = array('status'=>true,'data'=>$data, 
                'informasi'=>'Nama Menu: '. $data->nama_menu);
            return response()->json($respon);
        }
        $respon = array('status'=>false,'message'=>'Data Tidak Ditemukan');
        return response()->json($respon);
    }

    function submit_insert_menu_role(Request $r){
        
        if($this->ucc()){
            loadHelper('format');
            $id_role = $r->id_role;
            $id_menu = $r->id_menu;
            $ucc = $r->ucc;
            $ucd = $r->ucd;
            $ucu = $r->ucu;
            $exist = DB::table('role_menu')
                        ->where('id_role', $id_role)
                        ->where('id_menu', $id_menu)->count();

            if ($exist){
                $respon = array('status'=>false,'message'=>'Menu Sudah Ada!');
                return response()->json($respon);
            }
            $record = array('id_role'=>$id_role,
                'id_menu'=>$id_menu,
                'ucc'=>$ucc,
                'ucd'=>$ucd,
                'ucu'=>$ucu, 
                'uuid'=>$this->genUUID()
                ); 
            DB::table('role_menu')->insert($record);  
            $respon = array('status'=>true,'message'=>'Role Baru Berhasil Disimpan!');       
            return response()->json($respon);
        }else{
            $respon = array('status'=>false,'message'=>'Akses Ditolak!');
            return response()->json($respon);
        }
    }

    function submit_update_menu_role(Request $r){
        
        if($this->ucu()){
            loadHelper('format');
            $uuid = $r->uuid;
            $ucc = $r->ucc;
            $ucd = $r->ucd;
            $ucu = $r->ucu;
            $exist = DB::table('role_menu')
                        ->where('uuid', $uuid)->count();

            if (!$exist){
                $respon = array('status'=>false,'message'=>'Menu Tidak Ditemukan!');
                return response()->json($respon);
            }

            $record = array(
                'ucc'=>$ucc,
                'ucd'=>$ucd,
                'ucu'=>$ucu
                ); 

            DB::table('role_menu')->where('uuid', $uuid)->update($record);  
            $respon = array('status'=>true,'message'=>'Menu Role Berhasil Diupdate!');       
            return response()->json($respon);
        }else{
            $respon = array('status'=>false,'message'=>'Akses Ditolak!');
            return response()->json($respon);
        }
    }

    function submit_delete_menu_role(Request $r){
        if($this->ucd()){
            loadHelper('format');
            $uuid = $r->uuid;
            $exist = DB::table('role_menu')
                        ->where('uuid', $uuid)->count();

            if (!$exist){
                $respon = array('status'=>false,'message'=>'Menu Tidak Ditemukan!');
                return response()->json($respon);
            }

            DB::table('role_menu')->where('uuid', $uuid)->delete();  
            $respon = array('status'=>true,'message'=>'Menu Role Berhasil Dihapus!');       
            return response()->json($respon);
        }else{
            $respon = array('status'=>false,'message'=>'Akses Ditolak!');
            return response()->json($respon);
        }
    }


}
