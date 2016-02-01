<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\AdminLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller {

    public function getLogin() {
        $data = [
            "title" => "dCard Admin",
        ];

        return view("layout.login", $data);
    }

    public function postLogin(Request $request) {
        $input = $request->only(["email", "password"]);
        $admin = AdminLogin::where("email", "=", $input["email"])->first();

        if(!$admin){
            return redirect()->back();
        }

        if (Hash::check($input["password"], $admin->password)) {
            $session = [
                "email"    => $admin->email,
                "is_admin" => TRUE,
                "access"   => "all",
            ];

            Session::put('admin', $session);

            return redirect("app/dashboard");
        }

        return redirect()->back();
    }

    public function getLogout() {
        Session::flush();

        return redirect("admin/login");
    }
}
