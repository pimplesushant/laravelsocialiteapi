<?php

namespace Pimplesushant\Laravelsocialiteapi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\SocialAccount;
use Socialite;

class SocialController extends Controller
{
    public function social(Request $request) {
        
        $provider = $request->input('provider');
        switch($provider){
            case SocialAccount::SERVICE_FACEBOOK:
                $social_user = Socialite::driver(SocialAccount::SERVICE_FACEBOOK)->fields([
                    'name', 
                    'first_name', 
                    'last_name', 
                    'email'
                ]);
                break;
            case SocialAccount::SERVICE_GOOGLE:
                $social_user = Socialite::driver(SocialAccount::SERVICE_GOOGLE)
                ->scopes(['profile','email']);
                break;
            default :
                $social_user = null;
        }
        
        abort_if($social_user == null , 422,'Provider missing');
        
        $social_user_details = $social_user->userFromToken($request->input('access_token'));
        
        abort_if($social_user_details == null , 400,'Invalid credentials'); //|| $fb_user->id != $request->input('userID')

        $account = SocialAccount::where("provider_user_id",$social_user_details->id)
                ->where("provider",$provider)
                ->with('user')->first();
        
        if($account){
            return $this->issueToken($account->user);
        }
        else {
            $user = User::where("email",$social_user_details->getEmail())->first();
            if(!$user){
                $user = new User;
//                switch($provider){
//                     case SocialAccount::SERVICE_FACEBOOK:
//                         $user->first_name = $social_user_details->user['first_name'];
//                         $user->last_name = $social_user_details->user['last_name'];
//                         break;
//                     case SocialAccount::SERVICE_GOOGLE:
//                         $user->first_name = $social_user_details->user['name']['givenName'];
//                         $user->last_name = $social_user_details->user['name']['familyName'];
//                         break;
//                     default :
//                 }            
                $user->name = $social_user_details->getName();
		$user->email = $social_user_details->getEmail();
                $user->username = $social_user_details->getEmail();
                $user->password = Hash::make('social');
                $user->save();
            }
            $social_account = new SocialAccount;
            $social_account->provider = $provider;
            $social_account->provider_user_id = $social_user_details->id;
            $user->social_accounts()->save($social_account);
            return $this->issueToken($user);
        }
    } 
    
    private function issueToken(User $user) {
        
        $userToken = $user->token() ?? $user->createToken('socialLogin');

        return [
            "token_type" => "Bearer",
            "access_token" => $userToken->accessToken
        ];
    }
}
