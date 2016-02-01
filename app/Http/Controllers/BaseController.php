<?php namespace App\Http\Controllers;

use App\Http\Requests;

class BaseController extends Controller {
    protected $response;
    protected $member;
    protected $msg;
    protected $status;
    protected $responseStatus;

    public function __construct() {
        $this->member         = NULL;
        $this->msg            = NULL;
        $this->status         = FALSE;
        $this->responseStatus = [
            "msg"    => $this->msg,
            "status" => $this->status,
        ];
        $this->response       = [
            "member"         => $this->member,
            "responseStatus" => $this->responseStatus,
        ];
    }
}
