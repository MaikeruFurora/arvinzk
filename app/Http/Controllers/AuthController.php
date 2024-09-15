<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {

        return $this->authService = $authService;

    }

    public function index(){
        return view('auth.signin'); 
    }

    public function loginPost(LoginRequest $request){

        return $this->authService->login($request);

    }

    public function logout(){

        return $this->authService->signOut();
        
    }
}
