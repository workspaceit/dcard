<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Member;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberController extends BaseController {
    private $memberId;
    private $storeId;

    public function store(Request $request) {
        $input = $request->all();
        $rule  = [
            'email'       => 'required|unique:members,email',
            'first_name'  => 'required',
            'last_name'   => 'required',
            'user_type'   => 'required',
            'email_flag'  => 'required',
            'search_flag' => 'required',
            'zip_search'  => 'required',
            'fb_id'       => 'required|unique:members,fb_id',
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            $this->response = [
                "member"         => $this->member,
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];

            return $this->response;
        }

        $input["access_token"] = md5($input["fb_id"] . $input["email"]);

        $member              = Member::create($input);
        $member->member_code = Member::getMemberCode();
        $member->save();

        return $this->response = [
            "member"         => $member,
            "responseStatus" => [
                "msg"    => "Member Added Successfully",
                "status" => TRUE,
            ],
        ];
    }

    public function update(Request $request) {
        $input = $request->except('fb_id');
        $rule  = [
            'email'       => 'required|unique:members,email,' . Auth::user()->email,
            'first_name'  => 'required',
            'last_name'   => 'required',
            'user_type'   => 'required',
            'email_flag'  => 'required',
            'search_flag' => 'required',
            'zip_search'  => 'required',
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            $this->response = [
                "member"         => $this->member,
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];

            return $this->response;
        }

        $member = Auth::user();

        //$member = Member::fiindOrFail($id);

        if ($member->update($input)) {
            return $this->response = [
                "member"         => $member,
                "responseStatus" => [
                    "msg"    => "Member Updated Successfully",
                    "status" => TRUE,
                ],
            ];
        }

        return $this->response = [
            "member"         => $member,
            "responseStatus" => [
                "msg"    => "Member Updated Failed",
                "status" => FALSE,
            ],
        ];
    }

    public function destroy() {
        $member = Auth::user();
        if ($member->delete()) {
            return $this->response = [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Member Deleted Successfully",
                    "status" => TRUE,
                ],
            ];
        }

        return $this->response = [
            "member"         => $member,
            "responseStatus" => [
                "msg"    => "Member Deleted Failed",
                "status" => TRUE,
            ],
        ];
    }

    public function updatePreference(Request $request) {
        $input = $request->only(['email_preference', "search_preference", "zip_code"]);

        $rule = [
            "email_preference"  => "required",
            "search_preference" => "required",
            //"zip_code"          => "required",
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];
        }

        $this->memberId = Auth::user()->id;
        $member         = Member::find($this->memberId);

        if (!$member) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Invalid Member Identifier or Member Logout",
                    "status" => FALSE,
                ],
            ];
        }

        $data = [
            "email_flag"  => (strtolower($input["email_preference"]) == "true") ? 1 : 0,
            "search_flag" => (strtolower($input["search_preference"]) == 'zipsearch') ? "zipsearch" : "GPS",
            "zip_code"    => $input["zip_code"],
        ];

        if ($member->update($data)) {
            $sendMember = $member->toArray();
            array_forget($sendMember, "access_token");

            return [
                "member"         => $sendMember,
                "responseStatus" => [
                    "msg"    => "Preference Update Successfully",
                    "status" => TRUE,
                ],
            ];
        }

        return [
            "member"         => NULL,
            "responseStatus" => [
                "msg"    => "Preference Update Failed",
                "status" => FALSE,
            ],
        ];
    }

    public function setMerchant(Request $request) {
        $input = $request->all();
        $rule  = [
            "merchant_code" => "required",
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return $response = [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];
        }

        $member = Auth::user();

        if (!$member) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Login time Out !",
                    "status" => FALSE,
                ],
            ];
        }

        $memberId     = $member->id;
        $merchantCode = $input["merchant_code"];

        $store = Store::where("invite_code", "=", $merchantCode)->first();

        if ($store) {
            $member = Member::find($memberId);
            if ($member) {
                $member->store_id  = $store->store_id;
                $member->user_type = 1;
                if ($member->save()) {
                    $store = $member->store;

                    if ($store) {
                        $store->participator = ($store->participator == 1) ? TRUE : FALSE;
                        $store->category     = $member->store->category;
                    }

                    array_forget($member, ["access_token", "store"]);
                    array_forget($store, ["category_id"]);

                    return $response = [
                        "member"         => $member,
                        "store"          => $store,
                        "responseStatus" => [
                            "msg"    => "Member $member->last_name , now Merchant",
                            "status" => TRUE,
                        ],
                    ];
                }
            }

            return $response = [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Invalid Member Identifier",
                    "status" => FALSE,
                ],
            ];
        }

        return $response = [
            "member"         => NULL,
            "responseStatus" => [
                "msg"    => "Invalid Merchant Code",
                "status" => FALSE,
            ],
        ];
    }

    public function deleteMarchant() {
        $marchant = Auth::user();

        if (!$marchant) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Login time out",
                    "status" => FALSE,
                ],
            ];
        }

        $marchantId    = $marchant->id;
        $storeId       = $marchant->store_id;
        $totalMarchant = Member::where('store_id', '=', $storeId)
                               ->where('user_type', '=', 1)
                               ->count();

        if ($totalMarchant > 1) {
            $marchant->store_id  = NULL;
            $marchant->user_type = 3;
            $marchant->save();
        } else {
            Member::where('store_id', '=', $storeId)
                  ->update(['user_type' => 3, 'store_id' => NULL]);
        }

        $member = Member::find($marchantId);

        array_forget($member, ["access_token"]);

        return $data = [
            "member"         => $member,
            "store"          => NULL,
            "responseStatus" => [
                "msg"    => "$member->last_name's current role : member",
                "status" => TRUE,
            ],
        ];

    }

    public function setEmployee(Request $request) {
        $input = $request->all();
        $rule  = [
            "member_code" => "required",
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];
        }

        $merchant = Auth::user();

        if (!$merchant) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Login time out",
                    "status" => FALSE,
                ],
            ];
        }

        $this->memberId = $input["member_code"];
        $this->storeId  = $merchant->store_id;
        $store          = Store::find($this->storeId);
        $member         = Member::with("store")->with("store.category")->where("member_code", "=", $this->memberId)->first();

        if (!$store) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Invalid Store Identifier",
                    "status" => FALSE,
                ],
            ];
        }

        if (!$member) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Invalid Member Identifier",
                    "status" => FALSE,
                ],
            ];
        }

        if ($member->user_type != 3) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "This Member already under a store !",
                    "status" => FALSE,
                ],
            ];
        }

        $member->user_type = 2;
        $member->store_id  = $this->storeId;

        if ($member->save()) {
            $store = $member->store;
            array_forget($member, ["store", "access_token"]);
            array_forget($store, ["category_id"]);

            return [
                "member"         => $member,
                //"store"          => $store,
                "responseStatus" => [
                    "msg"    => "Employee Added Successfully !",
                    "status" => TRUE,
                ],
            ];
        }

        return [
            "member"         => $member,
            "responseStatus" => [
                "msg"    => "Employee Added Failed !",
                "status" => FALSE,
            ],
        ];
    }

    public function deleteEmployee(Request $request) {
        $input = $request->all();
        $rule  = [
            "member_id" => "required",
        ];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];
        }

        $this->memberId = $input["member_id"];
        $member         = Member::find($this->memberId);

        if (!$member) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Invalid Member Identifier",
                    "status" => FALSE,
                ],
            ];
        }

        $member->user_type = 3;
        $member->store_id  = NULL;

        if ($member->save()) {
            return [
                //"member"         => $member,
                "responseStatus" => [
                    "msg"    => "Employee Deleted Successfully !",
                    "status" => TRUE,
                ],
            ];
        }

        return [
            "member"         => $member,
            "responseStatus" => [
                "msg"    => "Employee Deleted Failed !",
                "status" => FALSE,
            ],
        ];
    }

    public function getMerchantDetails(Request $request) {
        $input  = $request->all();
        $member = NULL;

        if (@$input["id"] == "") {
            if (@$input["member_code"] == "") {
                return [
                    "member"         => $member,
                    "responseStatus" => [
                        "msg"    => "Member ID or Member Code Required",
                        "status" => FALSE,
                    ],
                ];
            }
        }

        if (@$input["id"]) {
            $this->memberId = @$input["id"];
            $member         = Member::find($this->memberId);
        } else if (@$input["member_code"]) {
            $this->memberId = @$input["member_code"];
            $member         = Member::where("member_code", "=", $this->memberId)->first();
        }

        if (!$member) {
            return [
                "member"         => $member,
                "responseStatus" => [
                    "msg"    => "Invalid Member Identifier",
                    "status" => FALSE,
                ],
            ];
        }

        array_forget($member, 'access_token');

        return [
            "member"         => $member,
            "responseStatus" => [
                "msg"    => NULL,
                "status" => TRUE,
            ],
        ];
    }

    public function getMerchantDetailsByCode($code) {
        $seller = Auth::user();

        if (!$seller || ($seller && @$seller->user_type != 2 && @$seller->user_type != 1)) {
            return [
                "responseStatus" => [
                    "msg"    => "Login time out",
                    "status" => FALSE,
                ],
            ];
        }

        $member = Member::where("member_code", "=", $code)->first();

        if (!$member) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Invalid Member Identifier !",
                    "status" => FALSE,
                ],
            ];
        }

        array_forget($member, ["access_token"]);

        return [
            "member"         => $member,
            "responseStatus" => [
                "msg"    => NULL,
                "status" => TRUE,
            ],
        ];
    }

    public function getMemberListByStoreId() {
        $merchant = Auth::user();

        if (!$merchant) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Login time out",
                    "status" => FALSE,
                ],
            ];
        }

        $storeId = $merchant->store_id;
        $members = Member::where("store_id", "=", $storeId)
                         ->where("user_type", "=", 2)
                         ->paginate(5)
                         ->toArray();

        if (!$members) {
            return [
                "member"         => NULL,
                "responseStatus" => [
                    "msg"    => "Invalid Store Identifier",
                    "status" => FALSE,
                ],
            ];
        }

        $count = 0;

        foreach ($members["data"] as $member) {
            array_forget($members["data"][$count], "access_token");
            $count++;
        }

        return [
            "member"         => $members,
            "responseStatus" => [
                "msg"    => "",
                "status" => TRUE,
            ],
        ];
    }

    public function getPreference() {
        $member = Auth::user();
        array_forget($member, "access_token");

        if (!$member) {
            return [
                "member"         => NULL,
                "saving"         => 0.00,
                "responseStatus" => [
                    "msg"    => "Session time out !",
                    "status" => FALSE,
                ],
            ];
        }

        return [
            "member"         => $member,
            "saving"         => 420.00,
            "responseStatus" => [
                "msg"    => NULL,
                "status" => TRUE,
            ],
        ];
    }
}