<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\GCM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Sly\NotificationPusher\Adapter\Apns as ApnsAdapter;
use Sly\NotificationPusher\Adapter\Gcm as GcmAdapter;
use Sly\NotificationPusher\Collection\DeviceCollection;
use Sly\NotificationPusher\Model\Device;
use Sly\NotificationPusher\Model\Message;
use Sly\NotificationPusher\Model\Push;
use Sly\NotificationPusher\PushManager;

class PushMassageController extends Controller {
    private $accessToken = "AIzaSyAKjyyTgAjn-NI43fP_Ny98As2QpsSxLCE";
    private $deviceId    = "hehe";
    private $message     = [];

    public function sendToAndroid($deviceId, $message) {
        $pushManager = new PushManager(PushManager::ENVIRONMENT_DEV);

        $gcmAdapter = new GcmAdapter([
            'apiKey' => $this->accessToken,
        ]);

        $devices = new DeviceCollection([
            new Device($deviceId),
        ]);
        $message = new Message("message", $message);

        $push = new Push($gcmAdapter, $devices, $message);
        $pushManager->add($push);
        $pushManager->push();
    }

    public function sendToIphone($deviceId, $message) {
        $pushManager = new PushManager(PushManager::ENVIRONMENT_DEV);

        $apnsAdapter = new ApnsAdapter([
            'certificate' => '/path/to/your/apns-certificate.pem',
        ]);

    }

    public function registerAndroidDevice(Request $requests) {
        $data = $requests->input();
        $rule = [
            "device_id" => "required",
        ];

        $validator = Validator::make($data, $rule);

        if ($validator->fails()) {
            return [
                "responseStatus" => [
                    "msg"    => $validator->errors()->first(),
                    "status" => FALSE,
                ],
            ];
        }

        $member = Auth::user();

        if (!$member) {
            return [
                "responseStatus" => [
                    "msg"    => "Login Time Out !",
                    "status" => FALSE,
                ],
            ];
        }

        $data["member_id"] = Auth::user()->id;

        $oldDevice = GCM::where("device_id", "=", $data["device_id"])->count();

        if ($oldDevice > 0) {
            return [
                "responseStatus" => [
                    "msg"    => "Device already registered !",
                    "status" => FALSE,
                ],
            ];
        }

        $device = GCM::create($data);

        if ($device) {
            return [
                "responseStatus" => [
                    "msg"    => "Device registered successfully !",
                    "status" => TRUE,
                ],
            ];
        }

        return [
            "responseStatus" => [
                "msg"    => "Device registered failed !",
                "status" => FALSE,
            ],
        ];
    }

    public function pushRequest() {
        $member = Auth::user();

        if (!$member) {
            return [
                "responseStatus" => [
                    "msg"    => "Login Time Out !",
                    "status" => FALSE,
                ],
            ];
        }

        $this->deviceId = $member->playStoreKey->device_id;
        $store          = $member->store;
        $this->message  = [
            "storeName"        => $store->store_name,
            "amountOff"        => $store->amount_off,
            "percentOff"       => $store->percent_off,
            "onSpent"          => $store->on_spent,
            "discount"         => 0,
            "totalSpent"       => 10,
            "currentAmount"    => 10,
            "transactionCount" => 1,
        ];

        $this->sendToAndroid($this->deviceId, $this->message);
    }
}
