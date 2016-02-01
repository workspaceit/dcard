<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\AdminLogin;
use App\Models\Category;
use App\Models\Member;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller {
    private $yelp;

    function __construct() {
        if (!Session::get('admin')) {
            return redirect("admin/login")->send();
        }

        $this->yelp = new YelpController();
    }

    public function getDashboard() {
        $data = [
            'title'    => "dCard Admin",
            "user"     => Member::all()->count(),
            "merchant" => Member::where("user_type", "=", 1)->count(),
            "employee" => Member::where("user_type", "=", 2)->count(),
            "customer" => Member::where("user_type", "=", 3)->count(),
        ];

        return view("app.dashboard", $data);
    }

    public function getStore($id) {
        $store = Store::find($id);

        if (!$store) {
            return redirect('app/store-list');
        }

        $data = [
            'title' => "store Details " . $store->store_name . " | dCard Admin",
            'store' => $store,
        ];

        return view('app.store.storeDetails', $data);
    }

    public function getStoreList() {
        $store = Store::orderBy("store_name", "ASC")->with('vote')->get();
        $data  = [
            "title"  => "Store List | dCard Admin",
            "stores" => $store,
        ];

        return view("app.storeList", $data);
    }

    public function getEditStore($id) {
        $store = Store::find($id);

        if (!$store) {
            return redirect('app/store-list');
        }

        $category = Category::lists('name', 'id');
        $data     = [
            "title"        => "Update Store : " . $store->store_name . " | dCard Admin",
            "store"        => $store,
            "category"     => $category,
            "participator" => ['1' => "Enrolled", '0' => 'Dispute'],
        ];

        return view('app.store.storeEditForm', $data);
    }

    public function postEditStore(Request $request, $id) {
        $store = Store::find($id);

        if (!$store) {
            return redirect('app/store-list');
        }

        $data = $request->input();

        $store->update($data);

        return redirect('app/store/' . $store->store_id);
    }

    public function getDeleteStore($id) {
        $store = Store::find($id);

        if (!$store) {
            return redirect('app/store-list');
        }

        if ($store->delete()) {
            Member::where('store_id', '=', $id)
                  ->update([
                      'user_type' => 3,
                      'store_id'  => NULL,
                  ]);
        }

        return redirect('app/store-list');
    }

    public function postSearchStore(Request $request) {
        $yelpData = $this->dataFormYelp($request);

        if (@$yelpData->error) {
            return redirect()->back();
        }

        $category = Category::lists("name", "id");

        $data = [
            "title"      => "Store Search Result in Yelp",
            "category"   => $category,
            "dCardStore" => Store::lists('yelp_id'),
            "yelpData"   => $yelpData,
        ];

        return view("app.storeResult", $data);
    }

    public function getNewStore($type = NULL) {
        $data = [
            'title' => "Add new Store | dCard Admin",
        ];

        if ($type === 'self') {
            $data['category']     = Category::lists('name', 'id');
            $data['participator'] = [0 => 'Dispute', 1 => 'Enrolled'];

            return view("app.store.selfStoreForm", $data);
        } else {
            return view("app.viewStoreForm", $data);
        }
    }

    public function postNewStore(Request $requests, $self = NULL) {
        if ($self === 'self') {
            $data                = $requests->input();
            $data['invite_code'] = Store::getString(10);
            $store               = Store::create($data);

            return redirect('app/store/' . $store->store_id);
        }

        $yelpID       = $requests->input("yelp_id");
        $categoryId   = $requests->input("category_id");
        $storeName    = $requests->input("store_name");
        $storeState   = $requests->input("store_state");
        $storeCity    = $requests->input("store_city");
        $storeZip     = $requests->input("store_zip");
        $storeCountry = $requests->input("store_country");
        $phone        = $requests->input("phone");
        $lon          = $requests->input("lon");
        $lat          = $requests->input("lat");

        if (!$yelpID) {
            return [
                "responseStatus" => [
                    "msg"    => "Store added Failed",
                    "status" => FALSE,
                ],
            ];
        }

        $oldStore = Store::lists("yelp_id");

        if (in_array($yelpID, $oldStore)) {
            return [
                "responseStatus" => [
                    "msg"    => "Store already in System !",
                    "status" => FALSE,
                ],
            ];
        }

        $store = Store::create([
            "invite_code"   => Store::getString(10),
            "yelp_id"       => @$yelpID,
            "category_id"   => @$categoryId,
            "store_name"    => @$storeName,
            "store_state"   => @$storeState,
            "store_city"    => @$storeCity,
            "store_zip"     => @$storeZip,
            "store_country" => @$storeCountry,
            "phone"         => @$phone,
            "lon"           => @$lon,
            "lat"           => @$lat,
            "participator"  => 0,
        ]);

        if (!$store) {
            return [
                "responseStatus" => [
                    "msg"    => "Store added Failed",
                    "status" => FALSE,
                ],
            ];
        }

        return [
            "responseStatus" => [
                "msg"    => "Store added Successfully",
                "status" => TRUE,
            ],
        ];
    }

    private function dataFormYelp(Request $request) {
        return $this->yelp->index($request);
    }

    public function sendStoreCodeForm($code) {
        $data = [
            'title' => "Send Invitation | dCard Admin",
            "code"  => $code,
        ];

        $store = Store::where("invite_code", "=", $code)->first();

        if (!$store) {
            return redirect()->back();
        }

        return view("app.sendCode", $data);
    }

    public function sendStoreCode(Request $request) {
        $data = $request->input();

        $rule = [
            "subject" => "required",
            "email"   => "required",
            "code"    => "required",
        ];

        $validator = Validator::make($data, $rule);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $store = Store::where("invite_code", "=", $data["code"])->first();

        if (!$store) {
            return redirect()->back()->withInput();
        }

        $body = "Your Invitation code : " . $data["code"];

        Mail::raw($body, function ($massage) use ($data) {
            $massage->from(session('admin')["email"], "dCard Admin");
            $massage->to($data["email"]);
            $massage->subject($data["subject"]);
        });

        return redirect("app/store-list");
    }

    public function categoryList() {
        $data = [
            "title"      => "Category List | dCard Admin",
            "categories" => Category::all(),
        ];

        return view("app.category.categoryList", $data);
    }

    public function category() {
        $data = [
            'title' => "Add Category | dCard Admin",
        ];

        return view("app.category.newCategory", $data);
    }

    public function storeCategory(Request $request) {
        $data = $request->input();
        $rule = [
            "name" => "required|unique:categories,name",
        ];

        $validator = Validator::make($data, $rule);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $admin = AdminLogin::where("email", "=", session('admin')["email"])->first();

        if (!$admin) {
            Session::flush();

            return redirect("admin/login");
        }

        $data["created_by"] = $admin->id;

        if (Category::create($data)) {
        }

        return redirect("store/category");
    }

    public function editCategory($id) {
        $category = Category::find($id);

        if (!$category) {
            return redirect()->back();
        }

        $data = [
            "title"    => "Edit Category | dCard Admin",
            "category" => $category,
        ];

        return view("app.category.updateCategory", $data);
    }

    public function updateCategory(Request $request, $id) {
        $data = $request->input();
        $rule = [
            "name" => "required|unique:categories,name," . $id,
        ];

        $validator = Validator::make($data, $rule);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Category::find($id);

        if (!$category) {
            return redirect("store/category");
        }

        $category->name = $data["name"];

        if ($category->save()) {
        }

        return redirect("store/category");
    }

    public function getUser() {
        $users = Member::all();
        $data  = [
            'title' => 'App Users List | dCard Admin',
            'users' => $users,
        ];

        return view('app.users', $data);
    }

    public function getAdmin() {
        $users = AdminLogin::all();
        $data  = [
            'title' => 'App Admin List | dCard Admin',
            'users' => $users,
            'admin' => session::get('admin'),
        ];

        return view('app.admin.admins', $data);
    }

    public function getAdminInfo($id) {
        $admin = AdminLogin::find($id);

        if (!$admin) {
            return redirect("app/admin");
        }

        $data = [
            'title' => "Admin Info | dCard Admin",
            'admin' => $admin,
        ];

        return view('app.admin.details', $data);
    }

    public function getEditAdmin($id) {
        $users = AdminLogin::find($id);

        if (!$users) {
            return redirect("app/admin");
        }

        $data = [
            'title' => 'Edit Admin Info | dCard Admin',
            'admin' => $users,
        ];

        return view('app.admin.edit', $data);
    }

    public function postEditAdmin(Request $request, $id) {
        $admin = AdminLogin::find($id);

        if (!$admin) {
            return redirect('app/admin');
        }

        $data = $request->input();

        $admin->update($data);

        return redirect('app/admin-info/' . $admin->id);
    }

    public function getDeleteAdmin($id) {
        $users = AdminLogin::find($id);

        if (!$users) {
            return redirect("app/admin");
        }

        $users->delete();

        return redirect("app/admin");
    }
}
