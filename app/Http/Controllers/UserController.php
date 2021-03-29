<?php

namespace App\Http\Controllers;

use Api;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class UserController extends Controller
{
    private $code;
    private $message;

    public function __construct()
    {
        $this->code = 200;
        $this->message = "success";
    }

    public function index()
    {
        $data = auth()->guard('api')->user();
        return Api::apiRespond($this->code, $data, $this->message);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return Api::apiResponseValidationFails('Validation error messages!', $validator->errors()->all());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Bcrypt($request->password),
            'remember_token' => Str::random(20)
        ]);

        $data['user'] = $user;
        $data['token'] = $user->createToken('digitiketintern')->accessToken;

        return Api::apiRespond($this->code, $data, $this->message);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return Api::apiResponseValidationFails('Login validation fails!', $validator->errors()->all(), 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $get_user = User::where('email', '=', $request->email)->first();
            if($get_user){
                $user = Auth::user();
                $data['user'] = $user;
                $data['token'] = $user->createToken('digitiketintern')->accessToken;
            } else {
                $this->message = "Akun belum terdaftar atau password anda salah";
                $this->code = 404;
                $data = [];
            }
        }

        return Api::apiRespond($this->code, $data, $this->message);
    }

    public function logout()
    {
        Auth::logout();
        return Api::apiRespond($this->code, [], $this->message);
    }

}
