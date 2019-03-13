<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Uuid;

class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usercreate:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        //create user ngadmin
        $record = ['username'=>'ngadmin', 
            'password'=>bcrypt('123456'), 
            'nama_pengguna'=>'Super Admin', 
            'email'=>'ngadmin@email.com',
            'telp'=>'kosong',
            'created_at'=>date('Y-m-d'),
            'uuid'=>$this->genUUID()
        ];

        DB::table('users')->insert($record);
    }

    function genUUID(){
        list($usec, $sec) = explode(" ", microtime());
        $time = ((float)$usec + (float)$sec);
        $time = str_replace(".", "-", $time);
        $panjang = strlen($time);
        $sisa = substr($time, -1*($panjang-5));
        return Uuid::generate(3,rand(10,99).rand(0,9).substr($time, 0,5).'-'.rand(0,9).rand(0,9)."-".$sisa,Uuid::NS_DNS);
    }
}
