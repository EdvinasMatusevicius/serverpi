<?php

namespace App\Console\Commands;

use App\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class AdminCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

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
    public function handle():void
    {
        $name = $this->enterName();
        $email = $this->enterEmail();
        $password = $this->enterPassword();
        
        $data = [
            'name'=>$name,
            'email'=>$email,
            'password'=>$password
        ];
        Admin::query()->create($data);
        
    }


    private function enterName():string
    {
        $name = $this->ask('Enter admin name');
        $validator = Validator::make(['name'=>$name],[
            'name'=>'required|regex:/^[a-zA-Z0-9]+$/|max:60'
        ]);
        if($validator->fails()){
            $this->error('name must be min 1 symbol, max 60 symbols and must be comprised of alpabetic characters or numbers');
            return $this->enterName();
        }
        return $name;
    }
    private function enterEmail():string
    {
        $email = $this->ask('Enter admin email');
        $validator = Validator::make(['email'=>$email],[
            'email'=>'required|unique:users|unique:admins|email|max:255'
        ]);
        if($validator->fails()){
            $this->error($validator->errors()->first('email'));
            return $this->enterEmail();
        }
        return $email;
    }
    private function enterPassword():string 
    {
        $password = $this->secret('Enter admin password');
        $passwordComfirm = $this->secret('Repeat admin password');

        $validator = Validator::make([
            'password'=>$password,
            'password_confirmation'=>$passwordComfirm
        ],[
            'password'=>'required|confirmed|min:8'
        ]);
        if($validator->fails()){
            $this->error($validator->errors()->first('password'));
            return $this->enterPassword();
        }
        return $password;

    }
}
