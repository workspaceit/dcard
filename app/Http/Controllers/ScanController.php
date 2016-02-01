<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ScanController extends BaseController {
    public function deleteLastScan(Request $requests) {
        $merchant = Auth::user();

        if (!$merchant) {
            return [
                "responseStatus" => [
                    "msg"    => "Login time out !",
                    "status" => $this->status,
                ],
            ];
        }

        $input = $requests->only("customer_id");
        $rule  = ["customer_id" => "required"];

        $validator = Validator::make($input, $rule);

        if ($validator->fails()) {
            return [
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => $this->status,
                ],
            ];
        }

        $customer = $input["customer_id"];
        $scanner  = $merchant->id;
        $scan     = Scan::orderBy("scan_id", "DESC")
                        ->where("customer_id", "=", $customer)
                        ->where("scanner_id", "=", $scanner)
                        ->first();

        if (!$scan) {
            return [
                "responseStatus" => [
                    "msg"    => "No Scan found for this Customer",
                    "status" => $this->status,
                ],
            ];
        }

        if ($scan->delete()) {
            return [
                "responseStatus" => [
                    "msg"    => "Last Scan deleted !",
                    "status" => TRUE,
                ],
            ];
        }

        return [
            "responseStatus" => [
                "msg"    => "Last Scan delete failed !",
                "status" => $this->status,
            ],
        ];
    }
}
