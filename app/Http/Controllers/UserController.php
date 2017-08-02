<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Event;
use App\Usertoevent;
use Response;
use Purifier;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class UserController extends Controller {

    public function store(Request $request) {

      //  $store = $_FILES;
        //return $store;
        //$file_array = [];
        //$name = "";
        //$i = 0;
        //foreach ($store as $key => $value) {
            //$i = $i + 1;
            //$name = $key.$i;
          //  return gettype($key);
       // }
        //return $i;
      // never assume the upload succeeded

      /*if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
          die("Upload failed with error code " . $_FILES['avatar']['error']);
      }

      $info = getimagesize($_FILES['avatar']['tmp_name']);
      if ($info === FALSE) {
          die("Unable to determine image type of uploaded file");
      }

      if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
          die("Not a gif/jpeg/png");
      }*/

        // get form input
        $username = $request->input('username'); 
        $email = $request->input('email'); 
        $password = $request->input('password'); 
        $avatar = $request->file('avatar');

        // validation rules
        $rules = [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required'
        ];

        // validate input
        $validator = Validator::make(Purifier::clean($request->all()), $rules);

        if ($validator->fails()) 
            return Response::json(['error' => 'You must fill out all fields.']);

        // ensure unique username and email
        $check = User::where('email', $email)->orWhere('name', $username)->first();

        if (!empty($check))
            return Response::json(['error' => 'Email or username already in use']);

        // 
        /*$process = new Process("mkdir images/$username");
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }   elseif ($process->isSuccessful()) {
          */
            
        $user = new User;
        $user->name = $username;
        $user->email = $email;
        $user->password = Hash::make($password);

        //return response()->json($file_array);

        if (!empty($avatar)) {
          $avatarName = $avatar->getClientOriginalName();
          $avatar->move('storage/avatar/', $avatarName);
          $user->avatar = $request->root().'/storage/avatar/'.$avatarName;
        }

        if ($user->save()) 
            return Response::json(['success' => 'User created successfully!']);
        
        Log::error('Error: Account not created');  
        return Response::json(['error' => 'Account not created']);  
    }

    public function SignIn(Request $request) {
        $rules = [
            'password' => 'required',
            'email' => 'required'
        ];

        $validator = Validator::make(Purifier::clean($request->all()), $rules);

        if ($validator->fails())
            return Responsee::json(['error' => 'You must enter email and password']) ;                           

        //$email =  $request->input('email'); 
        //$password = $request->input('password');

        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }

    public function test() {
        $user = User::where('email', 'tim@mail.com')->first();
        return $user;
    }


/*    public function process_test() {
        $process = new Process('(cd ../; php artisan greet:hello)');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }   elseif ($process->isSuccessful()) {
            return Response::json(['success' => 'proccess executed']);
        }
    }

    public function process_test_two() {
        $process = new Process('./mong email user > ~/bob.txt');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }   elseif ($process->isSuccessful()) {
            return Response::json(['success' => 'proccess executed']);
        }
    }*/

/*    public function foo() {
        //$store = $_ENV;
        //foreach ($store as $key => $value) {
         //   echo $key.'<br>';
        //}
        $data = $request->session()->all();
        return $data;
    }
    */
   /*    public function showStuff(Request $request)
    {
        //$value = $request->session()->get('ip_address');
        $store = $_SESSION;
        foreach ($store as $key => $value) {
          echo $key;
        }

        //
    }*/
    public function showImage() {
        $user = User::where('email', 'wtf@mail.com')->first();
        $avatar = $user->avatar;
        return $avatar;
    }
}
