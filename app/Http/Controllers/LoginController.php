<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function __construct()
    {
    }
    public function view(Request $request)
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        Session::put('state', $state = Str::random(40));
        $query = http_build_query([
            'client_id' => '9921fc22-5948-482f-b0db-e0c2de453909',
            'redirect_uri' => 'http://localhost:8080/callback', //url app
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
            "prompt" => true
        ]);

        return redirect('http://localhost:8000/oauth/authorize?' . $query); //url child app
    }

    public function callback(Request $request)
    {
        $state = Session::pull("state");
        throw_unless(strlen($state) > 0 && $state == $request->state, InvalidArgumentException::class);

        $response = Http::asForm()->post(
            'http://localhost:8000/oauth/token', //url child app
            [
                'grant_type' => 'authorization_code',
                'client_id' => '9921fc22-5948-482f-b0db-e0c2de453909',
                'client_secret' => 'bXN0yzWie51lCT0IGUI8GzU9DTotL6mQ8YVxSD3E',
                'redirect_uri' =>  'http://localhost:8080/callback', //url app
                'code' => $request->code,
            ]
        );

        Session::put('accessInfo', $response->json());
        return redirect()->route('get.user');
    }

    public function getUser()
    {
        $accessInfo = Session::get('accessInfo');
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessInfo['access_token'],
        ])->get('http://localhost:8000/api/user');

        $dataResponse = $response->json();
        $user = User::find($dataResponse['id']);
        Auth::guard('web')->login($user);

        return redirect()->route('home');
    }
}
