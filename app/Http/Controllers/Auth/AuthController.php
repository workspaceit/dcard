<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\Member;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController {
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;
    protected $loginPath           = "";
    protected $redirectPath        = "";
    protected $redirectAfterLogout = "";

    public function __construct(Guard $auth, Registrar $registrar) {
        parent::__construct();
        $this->auth      = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function postLogin(Request $request, $old = TRUE) {
        $accessToken = $request->get("access_token");

        if ($accessToken == "" || $accessToken == NULL) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Access Token Required for Login",
                    "status" => FALSE,
                ],
            ];
        }

        $member = Member::where("access_token", "=", $accessToken)->first();

        Auth::login($member);

        if (Auth::user()) {
            $store = $member->store;

            if ($store) {
                $store->participator = ($store->participator == 1) ? TRUE : FALSE;
                $store->category     = $member->store->category;
            }

            if ($old) {
                array_forget($member, ["access_token"]);
            }

            array_forget($member, ["store"]);
            array_forget($store, ["category_id"]);

            return $response = [
                "member"         => $member,
                "store"          => $store,
                "responseStatus" => [
                    "msg"    => "Logged in Successfully",
                    "status" => TRUE,
                ],
            ];
        }

        return $data = [
            "member"         => NULL,
            "responseStatus" => [
                "msg"    => "Logged in failed try again",
                "status" => FALSE,
            ],
        ];
    }

    public function postRegister(Request $request) {

        $fbId        = $request->get("fb_id");
        $checkMember = Member::where('fb_id', '=', $fbId)->first();

        if ($checkMember) {
            $request['access_token'] = $checkMember->access_token;

            return $this->postLogin($request, $old = FALSE);

            //$this->responseStatus ["msg"] = "Member Already Exist";

            return $this->response = [
                "member"         => $this->member,
                "responseStatus" => [
                    "msg"    => "Logged in Successfully",
                    "status" => TRUE,
                ],
            ];
        }

        $input = $request->all();
        $rule  = [
            'email'      => 'required|unique:members,email',
            'first_name' => 'required',
            'last_name'  => 'required',
            'fb_id'      => 'required|unique:members,fb_id',
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return $this->response = [
                "member"         => $this->member,
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];
        }

        $member = Member::create($request->all());

        $member->member_code  = $member->id . mt_rand(1000, 9999);
        $member->access_token = md5($input["fb_id"] . $input["email"]);
        $member->search_flag  = "GPS";
        $member->save();

        Auth::login($member);

        $member = Member::find($member->id);
        $store  = $member->store;

        if ($store) {
            $store->participator = ($store->participator == 1) ? TRUE : FALSE;
            $store->category     = $member->store->category;
        }

        array_forget($member, ["store"]);
        array_forget($store, ["category_id"]);

        return $response = [
            "member"         => $member,
            "store"          => $store,
            "responseStatus" => [
                "msg"    => NULL,
                "status" => TRUE,
            ],
        ];
    }

}