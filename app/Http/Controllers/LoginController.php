<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Riddle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authSchRedirect(Request $request)
    {
        Log::create('auth.sch.redirect','','authsch.redirect');

        $auth_sch_id = env('AUTH_SCH_ID');
        $auth_sch_key = env('AUTH_SCH_KEY');
        $ip = md5($request->ip());
        $redirect_uri = env('APP_URL') . "/auth/callback";

        $scope = [
            'basic',
            'displayName',
            'sn',
            'givenName',
            'mail'
        ];

        $new_scope = "";

        foreach($scope as $scope_part) {
            $new_scope .= $scope_part . "+";
        }

        $url = "https://auth.sch.bme.hu/site/login?client_id=" . $auth_sch_id . "&redirect_uri=" . $redirect_uri
            . "&scope=" . $new_scope . "&response_type=code&state=" . $ip;

        return redirect($url);
    }

    public function authSchCallback(Request $request)
    {
        Log::create('auth.sch.callback','','authsch.callback');

        $code = $request->get('code');

        $auth_sch_id = env('AUTH_SCH_ID');
        $auth_sch_key = env('AUTH_SCH_KEY');

        $url = "https://auth.sch.bme.hu/oauth2/token";
        $post_fields = "grant_type=authorization_code&code=$code";

        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_USERPWD,"$auth_sch_id:$auth_sch_key");
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$post_fields);
        $result = curl_exec($curl);

        curl_close($curl);

        $result = json_decode($result);

        $access_token = $result->access_token;

        $url = "https://auth.sch.bme.hu/api/profile?access_token=$access_token";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result);

        $user = User::where('email',$result->mail)->get();

        if($user->isEmpty()) {
            $user = new User();

            $user->name = $result->displayName;
            $user->surname = $result->sn;
            $user->email = $result->mail;
            $user->given_names = $result->givenName;
            $user->internal_id = $result->internal_id;

            $user->save();
        }elseif($user->first()->internal_id==null){
            $user = $user->first();
            $user->internal_id = $result->internal_id;
            $user->name = $result->displayName;
            $user->surname = $result->sn;
            $user->given_names = $result->givenName;
            $user->save();
        }else{
            $user = $user->first();
        }

        Auth::loginUsingId($user->id);

        return redirect(route('index'));
    }

    public function register(Request $request)
    {

        if(Auth::check()){
            abort(403);
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $password2 = $request->input('password2');

        if($password!=$password2){
            return redirect(route('login', ['error' => 1]));
        }
        if(User::where('email',$email)->count()!=0) {
            return redirect(route('login',['error' => 2]));
        }
        if(strlen($password)<8){
            return redirect(route('login',['error' => 3]));
        }

        $user = new User();
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->surname = $request->input('surname');
        $user->given_names = $request->input('given_names');
        $user->name = $user->surname." ".$user->given_names;
        $user->save();
        Auth::login($user);

        Log::create('register',$user->id,'register',$user);

        return redirect(route('index'));
    }

    public function logout()
    {
        Log::create('page.view',Auth::user()->id,'logout',Auth::user());

        Auth::logout();

        return redirect(route('login'));
    }

    public function check(Request $request)
    {
        Log::create('page.view','','login.check');

        if(Auth::check()){
            abort(403);
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        Auth::attempt($credentials);

        return redirect(route('index'));
    }

    public function apiLogin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        $user = User::all()
            ->where('email',$email)
            ->first();

        if($user!=null){
            if($user->password == null){
                return response()->json([
                    'success' => false,
                    'password' => false
                ]);
            }
        }

        Auth::attempt($credentials);

        if(Auth::check()){
            return response()->json(['api_key' => Auth::user()->generateNewApiKey()]);
        }else{
            return response()->json([
                'success' => false,
                'password' => true
            ]);
        }
    }

    public function apiRegister(Request $request)
    {
        if(!$request->input('email') || !$request->input('password') || !$request->input('password2') || !$request->input('given_names') || !$request->input('surname')){
            abort(403);
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $password2 = $request->input('password2');

        if($password != $password2){
            return response()->json([
                'success' => false,
                'reason' => 'password_not_match'
            ]);
        }

        if(strlen($password)<8){
            return response()->json([
                'success' => false,
                'reason' => 'password_length'
            ]);
        }

        $users = User::all()
            ->where('email',$email)
            ->count();

        if($users>0){
            return response()->json([
                'success' => false,
                'reason' => 'email_exists'
            ]);
        }

        $user = new User();
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->given_names = $request->input('given_names');
        $user->surname = $request->input('surname');
        $user->name = $request->input('surname') . " " . $request->input('given_names');
        $user->save();

        return response()->json([
            'api_key' => $user->generateNewApiKey()
        ]);
    }

}
