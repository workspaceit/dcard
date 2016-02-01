<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Category;
use App\Models\Member;
use App\Models\Scan;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StoreController extends BaseController {
    private $store_id;
    private $percent_off;
    private $amount_off;
    private $on_spent;

    public function __construct() {
        parent::__construct();
        $this->store_id    = 0;
        $this->percent_off = 0;
        $this->amount_off  = 0;
        $this->on_spent    = 0;
    }

    public function index(Request $request) {
        $orderBy = $request->get("order_by");

        if (!in_array($orderBy, ["name", "category"])) {
            $orderBy = "name";
        }

        $store = Store::join("categories", "categories.iz")
                      ->with("category")
                      ->orderBy("category.name", "DESC")
                      ->paginate(5)
                      ->toArray();
        //->toSql();die;

        array_forget($store, "category_id");

        return [
            "store"          => $store,
            "responseStatus" => [
                "msg"    => NULL,
                "status" => TRUE,
            ],
        ];
    }

    public function create(Request $request) {
        $yelp = new YelpController();
        $data = $yelp->index($request);

        $count   = 0;
        $updated = 0;

        if (@$data->error) {
            return $data->error;
        } else {
            if ($data->total > 0) {
                foreach ($data->businesses as $store) {
                    $oldData = Store::where("yelp_id", "=", $store->id)->first();

                    $info = [
                        "invite_code"   => Store::getString(10),
                        "yelp_id"       => $store->id,
                        "store_name"    => $store->name,
                        "store_state"   => $store->location->state_code,
                        "store_city"    => $store->location->city,
                        "store_zip"     => $store->location->postal_code,
                        "store_country" => $store->location->country_code,
                        "log"           => $store->location->coordinate->longitude,
                        "lat"           => $store->location->coordinate->latitude,
                    ];

                    if (!$oldData) {
                        Store::create($info);
                        $count++;
                    } else {
                        $oldInfo = [
                            "invite_code"   => $oldData->merchant_invite_code,
                            "yelp_id"       => $oldData->id,
                            "store_name"    => $oldData->store_name,
                            "store_state"   => $oldData->store_state,
                            "store_city"    => $oldData->store_city,
                            "store_zip"     => $oldData->store_zip,
                            "store_country" => $oldData->store_country,
                            "long"          => $oldData->lon,
                            "lat"           => $oldData->lat,
                        ];

                        if (!empty(array_diff($info, $oldInfo))) {
                            $oldData->store_name    = $store->name;
                            $oldData->store_state   = $store->location->state_code;
                            $oldData->store_city    = $store->location->city;
                            $oldData->store_zip     = $store->location->postal_code;
                            $oldData->store_country = $store->location->country_code;
                            $oldData->lon           = $store->location->coordinate->longitude;
                            $oldData->lat           = $store->location->coordinate->latitude;

                            if ($oldData->save()) {
                                $updated++;
                            }
                        }
                    }
                }
            }
        }

        return $this->response = [
            "responseStatus" => [
                "msg"    => $count . " Store(s) Inserted in Database and " . $updated . " Store(s) Updated",
                "status" => TRUE,
            ],
        ];
    }

    public function store(Request $request) {
        $input = $request->all();
        $rule  = [
            "store_name" => "required",
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

        $input["invite_code"] = Store::getString(8);

        return $input;
    }

    public function show($id) {
        $member = Auth::user();
        $scan   = NULL;

        if (!$member) {
            return [
                "store"          => NULL,
                "scan"           => NULL,
                "responseStatus" => [
                    "msg"    => "Login time out",
                    "status" => FALSE,
                ],
            ];
        }

        $store = Store::with("category")->where("store_id", "=", $id)->first();

        if (!$store) {
            return [
                "store"          => NULL,
                "scan"           => NULL,
                "responseStatus" => [
                    "msg"    => "Invalid Store Identifier",
                    "status" => FALSE,
                ],
            ];
        }

        $scan = Scan::where("store_id", "=", $id)
                    ->where("customer_id", "=", $member->id)
                    ->orderBy("scan_id", "DESC")
                    ->first();

        if ($member->user_type == 3) {
            if (!$scan) {
                $scan = NULL;
            }
        }

        array_forget($store, "category_id");

        return [
            "store"          => $store,
            "scan"           => $scan,
            "responseStatus" => [
                "msg"    => NULL,
                "status" => TRUE,
            ],
        ];
    }

    public function updatePrice(Request $request) {
        $input = $request->all();
        $rule  = [
            "percent_off" => "required",
            //"category_id" => "required",
            "amount_off"  => "required",
            "on_spent"    => "required",
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            $this->msg = $validator->errors()->first();

            return $this->response = [
                "store"          => NULL,
                "responseStatus" => [
                    "msg"    => $this->msg,
                    "status" => $this->status,
                ],
            ];
        }

        $merchant = Auth::user();

        if (!$merchant) {
            return [
                "store"          => NULL,
                "responseStatus" => [
                    "msg"    => "Login time out",
                    "status" => $this->status,
                ],
            ];
        }

        $store = Store::find($merchant->store_id);

        if (!$store) {
            $this->msg = "Invalid Store Identifier !";

            return $this->response = [
                "store"          => NULL,
                "responseStatus" => [
                    "msg"    => $this->msg,
                    "status" => $this->status,
                ],
            ];
        }

        if ($store->update($input)) {
            $this->msg = "Discount seated Successfully !";

            $store->participator = ($store->participator == 1) ? TRUE : FALSE;
            $store->category     = $store->category;

            //$store = Store::with("category")->where("store_id", "=", $merchant->store_id)->first();
            array_forget($store, "category_id");

            return $this->response = [
                "store"          => $store,
                "responseStatus" => [
                    "msg"    => $this->msg,
                    "status" => TRUE,
                ],
            ];
        }

        $this->msg           = "Discount set failed !";
        $store->participator = ($store->participator == 1) ? TRUE : FALSE;

        return $this->response = [
            "store"          => $store,
            "responseStatus" => [
                "msg"    => $this->msg,
                "status" => FALSE,
            ],
        ];

    }

    public function updateCategory(Request $request) {
        $input = $request->all();
        $rule  = [
            "category_id" => "required",
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            $this->msg = $validator->errors()->first();

            return $this->response = [
                "store"          => NULL,
                "responseStatus" => [
                    "msg"    => $this->msg,
                    "status" => $this->status,
                ],
            ];
        }

        $merchant = Auth::user();

        if (!$merchant) {
            return [
                "store"          => NULL,
                "responseStatus" => [
                    "msg"    => "Login time out !",
                    "status" => $this->status,
                ],
            ];
        }

        $store_id = $merchant->store_id;
        $store    = Store::find($store_id);

        if (!$store) {
            $this->msg = "Invalid Store Identifier";

            return $this->response = [
                "store"          => NULL,
                "responseStatus" => [
                    "msg"    => $this->msg,
                    "status" => $this->status,
                ],
            ];
        }

        $store->category_id = $input["category_id"];

        if ($store->save()) {
            $this->msg    = "Store Category Updated Successfully !";
            $this->status = TRUE;

            return $this->response = [
                "store"          => $store,
                "responseStatus" => [
                    "msg"    => $this->msg,
                    "status" => $this->status,
                ],
            ];
        }

        $this->msg = "Store Category Update Failed !";

        return $this->response = [
            "store"          => $store,
            "responseStatus" => [
                "msg"    => $this->msg,
                "status" => $this->status,
            ],
        ];
    }

    public function scanStore(Request $request) {
        $input = $request->all();
        $rule  = [
            "customer_id" => "required",
            "amount"      => "required",
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return [
                "transaction"    => NULL,
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];
        }

        $amount       = $input["amount"];
        $customer     = $input["customer_id"];
        $customerInfo = Member::find($customer);
        $deviceId     = @$customerInfo->playStoreKey->device_id;
        $merchant     = Auth::user();
        $merchantId   = $merchant->id;
        $store        = Store::find($merchant->store_id);
        $scan         = Scan::orderBy('scan_id', "DESC")
                            ->where('customer_id', "=", $customer)
                            ->where('scanner_id', "=", $merchantId)
                            ->first();
        $data         = [
            "customer_id"       => $customer,
            "scanner_id"        => $merchant->id,
            "store_id"          => $merchant->store_id,
            "transaction_price" => $amount,
            "saving"            => 0,
            "paid"              => $amount,
            "cumulative_paid"   => $amount,
            "cumulative_saving" => 0,
            "cumulative_count"  => 1,
        ];
        $GCM          = new PushMassageController();
        $message      = [
            "storeName"        => $store->store_name,
            "amountOff"        => $store->amount_off,
            "percentOff"       => $store->percent_off,
            "onSpent"          => $store->on_spent,
            "discount"         => 0,
            "totalSpent"       => $amount,
            "currentAmount"    => $amount,
            "transactionCount" => 1,
        ];

        if (!$customerInfo->playStoreKey) {
            return [
                "responseStatus" => [
                    "msg"    => "Scan Add failed !",
                    "status" => FALSE,
                ],
            ];
        }

        if (!$scan) {

            if ($amount >= $store->on_spent) {
                $data["saving"]            = $store->amount_off;
                $data["paid"]              = $amount - $store->amount_off;
                $data["cumulative_paid"]   = $data["paid"];
                $data["cumulative_saving"] = $data["saving"];

                $message["discount"]      = $data["saving"];
                $message["totalSpent"]    = $data["cumulative_paid"];
                $message["currentAmount"] = $data["paid"];
            }

            if (Scan::create($data)) {
                $GCM->sendToAndroid($deviceId, $message);

                return [
                    "responseStatus" => [
                        "msg"    => "Scan Successfully Added !",
                        "status" => TRUE,
                    ],
                ];
            }
        } else {
            $data["transaction_price"] = $amount;
            $data["saving"]            = 0;
            $data["paid"]              = $amount;
            $data["cumulative_paid"]   = $amount + $scan->cumulative_paid;
            $data["cumulative_saving"] = $scan->cumulative_saving;
            $data["cumulative_count"]  = $scan->cumulative_count + 1;

            $message["discount"]         = $data["saving"];
            $message["totalSpent"]       = $data["cumulative_paid"];
            $message["transactionCount"] = $data["cumulative_count"];

            if ($store->amount_off > 0) {
                if ($amount + $scan->cumulative_paid >= $store->on_spent * (($scan->cumulative_saving / $store->amount_off) + 1)) {
                    $data["saving"]            = $store->amount_off;
                    $data["paid"]              = $amount - $store->amount_off;
                    $data["cumulative_paid"]   = $data["paid"] + $scan->cumulative_paid;
                    $data["cumulative_saving"] = $data["saving"] + $scan->cumulative_saving;

                    $message["discount"]      = $data["saving"];
                    $message["totalSpent"]    = $data["cumulative_paid"];
                    $message["currentAmount"] = $data["paid"];
                }
            }

            if (Scan::create($data)) {
                $GCM->sendToAndroid($deviceId, $message);

                return [
                    "responseStatus" => [
                        "msg"    => "Scan Successfully Added !",
                        "status" => TRUE,
                    ],
                ];
            }
        }

        return [
            "responseStatus" => [
                "msg"    => "Scanned Failed !",
                "status" => FALSE,
            ],
        ];
    }

    public function search(Request $request) {
        if (!Auth::user()) {
            return [
                "store"          => NULL,
                "responseStatus" => [
                    "msg"    => "Login time out !",
                    "status" => FALSE,
                ],
            ];
        }

        $searchFlag = Auth::user()->search_flag;
        $zipCode    = Auth::user()->zip_code;

        if (strtolower($searchFlag) == 'zipsearch' && (!$zipCode)) {
            return [
                "store"          => NULL,
                "responseStatus" => [
                    "msg"    => "Zip Code Empty",
                    "status" => FALSE,
                ],
            ];
        }

        $categoryId = $request->get('category_id');
        $categoryId = explode(',', trim($categoryId, ','));

        $category = Category::whereIn("id", $categoryId)->select("slug")->get();

        if ($category) {
            $input['category_filter'] = '';
            $i                        = 1;
            $e                        = count($category);

            foreach ($category as $cat) {
                $input['category_filter'] .= $cat->slug;
                if ($i > 0 && $i < $e) {
                    $input['category_filter'] .= ',';
                }
                $i++;
            }
        }

        //$input['term']        = @$term->name;
        $input['offset']      = $request->get('offset');
        $input['location']    = $request->get('location');
        $input['limit']       = $request->get('limit');
        $input['radius']      = $request->get('distance');
        $input['local_store'] = $request->get('local_store');
        $input['lat']         = $request->get('lat');
        $input['lon']         = $request->get('lon');

        if ($searchFlag == 'zipsearch') {
            $input['location'] = 'Zip code ' . $zipCode;
        }

        $store      = NULL;
        $result     = NULL;
        $yelpId     = NULL;
        $localStore = $input['local_store'];
        $yelp       = new YelpController();

        $data = $yelp->storeSearch($input);

        if (@$data->error) {
            return [
                "store"          => $result,
                "responseStatus" => [
                    "msg"    => "An error occur",
                    "status" => FALSE,
                ],
            ];
        } else {
            if ($data->total > 0) {
                $store = Store::where("participator", "=", 1)->lists("yelp_id", "store_id");

                $count = 0;

                foreach ($data->businesses as $searchStore) {
                    if (in_array($searchStore->id, $store)) {
                        $searchStore->participator = TRUE;
                        $searchStore->store_id     = array_search($searchStore->id, $store);
                    } else {
                        $searchStore->participator = FALSE;
                        $searchStore->store_id     = 0;
                    }

                    if ($localStore == 1) {
                        if (in_array($searchStore->id, $store)) {
                            $result[$count] = [
                                "store_id"      => @$searchStore->store_id,
                                "yelp_id"       => @$searchStore->id,
                                "store_name"    => @$searchStore->name,
                                "store_state"   => @$searchStore->location->state_code,
                                "store_city"    => @$searchStore->location->city,
                                "store_zip"     => @$searchStore->location->postal_code,
                                "store_country" => @$searchStore->location->country_code,
                                "phone"         => @$searchStore->phone,
                                "lon"           => @$searchStore->location->coordinate->longitude,
                                "lat"           => @$searchStore->location->coordinate->latitude,
                                "participator"  => @$searchStore->participator,
                                "categories"    => @$searchStore->categories,
                            ];
                            $count++;
                        }
                    } else {
                        $result[$count] = [
                            "store_id"      => @$searchStore->store_id,
                            "yelp_id"       => @$searchStore->id,
                            "store_name"    => @$searchStore->name,
                            "store_state"   => @$searchStore->location->state_code,
                            "store_city"    => @$searchStore->location->city,
                            "store_zip"     => @$searchStore->location->postal_code,
                            "store_country" => @$searchStore->location->country_code,
                            "phone"         => @$searchStore->phone,
                            "lon"           => @$searchStore->location->coordinate->longitude,
                            "lat"           => @$searchStore->location->coordinate->latitude,
                            "participator"  => @$searchStore->participator,
                            "categories"    => @$searchStore->categories,
                        ];
                        $count++;
                    }
                }

                return [
                    "total"          => @$data->total,
                    "store"          => $result,
                    "responseStatus" => [
                        "msg"    => NULL,
                        "status" => TRUE,
                    ],
                ];
            }
        }

        return [
            "store"          => NULL,
            "responseStatus" => [
                "msg"    => "No result found",
                "status" => FALSE,
            ],
        ];
    }

    public function getCategory() {
        $category = Category::orderBy("name", "ASC")->select(["id", "name"])->get();

        if ($category) {
            return [
                "category"       => $category,
                "responseStatus" => [
                    "msg"    => NULL,
                    "status" => TRUE,
                ],
            ];
        }

        return [
            "category"       => NULL,
            "responseStatus" => [
                "msg"    => "No category Found",
                "status" => FALSE,
            ],
        ];
    }

    public function changeStatus(Request $request) {
        $input = $request->input();
        $rule  = [
            "status"   => "required",
            "store_id" => "required",
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return [
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];
        }

        $store        = Store::find($input["store_id"]);
        $participator = ($input["status"] == 0) ? 0 : 1;

        if (!$store) {
            return [
                "responseStatus" => [
                    "msg"    => "Invalid Store Identifier !",
                    "status" => FALSE,
                ],
            ];
        }

        $store->participator = $participator;

        if ($store->save()) {
            return [
                "responseStatus" => [
                    "msg"    => NULL,
                    "status" => TRUE,
                ],
            ];
        }

        return [
            "responseStatus" => [
                "msg"    => "Operation Failed !",
                "status" => FALSE,
            ],
        ];
    }

    public function getCategoryFromYelp() {
        $file = "https://raw.githubusercontent.com/Yelp/yelp-api/master/category_lists/en/category.json";
        $data = file_get_contents($file);
        $data = json_decode($data);
        echo "<pre/>";

        //print_r($data);

        foreach ($data as $dt) {
            $parent = Category::where("slug", "=", $dt->alias)->first();

            if (!$parent) {
                $parent = Category::create([
                    "name"       => $dt->title,
                    "slug"       => $dt->alias,
                    "created_by" => Auth::user()->id,
                    "parent"     => 0,
                ]);
            }

            if ($parent) {
                foreach ($dt->category as $ch) {
                    $child = Category::where("slug", "=", $ch->alias)->first();

                    if (!$child) {
                        Category::create([
                            "name"       => $ch->title,
                            "slug"       => $ch->alias,
                            "created_by" => Auth::user()->id,
                            "parent"     => $parent->id,
                        ]);
                    }
                }
            }
        }

        return redirect("store/category");
    }
}
