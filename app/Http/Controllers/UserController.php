<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\interfaces\IBooksRepository;
use Illuminate\Support\Facades\DB;
use stdClass;

class UserController extends Controller
{
    //


    public function __construct(IBooksRepository $repo)
    {
        $this->_repo = $repo;
    }
    private $request;
    private $_repo;
    private function getJsonKey($keyName='') {
        return $this->request->json()->get($keyName);
    }

    public function LoginUser(Request $request) {
        try
        {
            
        $this->request = $request;
        $email = $this->getJsonKey('email');
        $password = $this->getJsonKey('password');

        if($email == null || $password == null) {
            return response(json_encode(['status'=>'failed', 'data'=>'email and pasword is required']), 400, ['Content-Type'=>'application/json']);
        }

        $phash = md5($password);

        $user = DB::table('books')->where('email', $email)
                        ->where('password', $phash)->get();

        if($user == null || count($user) == 0) {
            return response(json_encode(['status'=>'failed', 'data'=>'invalid login credentials']), 400, ['Content-Type'=>'application/json']);
        }
        $std = new stdClass();
        $std->status = "success";
        $std->sessionToken = now();
        $std->inSession = true;
        $std->details = $user;

            return response(json_encode(['status'=>'success', 'data'=>$std]), 200, ['Content-Type'=>'application/json']);
        }
        catch(Exception $es) {
            return response(json_encode(['status'=>'failed', 'data'=>$es->getMessage()]), 500, ['Content-Type'=>'application/json']);
          }
    }

    public function AddNewUser(Request $request) 
    {
        try
        {
            $this->request = $request;
            $user = new User();
            $user->name = $this->getJsonKey('name');
            $user->email = $this->getJsonKey('email');
            $user->password = md5($this->getJsonKey('password'));
    
            $createResult = $this->_repo->AddNewUser($user);
    
            if($createResult == 1 || $createResult == true) {
                return response(json_encode(['status'=>'success', 'data'=>'${$user->name} successfully registered']), 200, ['Content-Type'=>'application/json']);
            }
    
            return response(json_encode(['status'=>'failed', 'data'=>'error adding new user']), 400, ['Content-Type'=>'application/json']);
        }
        catch(Exception $es) {
          return response(json_encode(['status'=>'failed', 'data'=>$es->getMessage()]), 500, ['Content-Type'=>'application/json']);
        }
        
    }
    
}
