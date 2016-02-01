<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Store;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class VoteController extends BaseController {
    public function __construct() {
        parent::__construct();
        $this->member = Auth::user();

        if (!$this->member) {
            return [
                "msg"    => "Login time out !",
                "status" => $this->status,
            ];
        }
    }

    public function getEnroll($storeId) {
        $store = Store::find($storeId);

        if (!$store) {
            return [
                "msg"    => "Invalid store Identifier !",
                "status" => $this->status,
            ];
        }

        $data = [
            'store_id'    => $storeId,
            'customer_id' => $this->member->id,
        ];

        if ($this->getCheck($storeId)) {
            return [
                "msg"    => "Already Voted !",
                "status" => FALSE,
            ];
        }

        if (Vote::create($data)) {
            return [
                "msg"    => "Voted Successfully !",
                "status" => TRUE,
            ];
        }

        return [
            "msg"    => "Vote Failed !",
            "status" => $this->status,
        ];
    }

    public function getCheck($storeId) {
        $vote = Vote::where('customer_id', '=', $this->member->id)
                    ->where('store_id', '=', $storeId)
                    ->get()
                    ->count();

        if ($vote > 0) {
            return TRUE;
        }

        return FALSE;
    }
}
