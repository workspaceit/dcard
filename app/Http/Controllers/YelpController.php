<?php

namespace app\Http\Controllers;

use App\Http\Lib\Yelp\OAuthConsumer;
use App\Http\Lib\Yelp\OAuthRequest;
use App\Http\Lib\Yelp\OAuthSignatureMethod_HMAC_SHA1;
use App\Http\Lib\Yelp\OAuthToken;
use Illuminate\Http\Request;

class YelpController extends BaseController {
    //use App\Http\Controllers\BaseController;
    //use App\Http\Lib\Yelp\OAuthUtil;
    protected $CONSUMER_KEY    = "8Mk8bB1Gel5dI6FRdYg5Sw";
    protected $CONSUMER_SECRET = "QFdkPa5kKhPHv3L2In4tZCSiVWA";
    protected $TOKEN           = "5bhFaoDOKEo7Cys9LDGxNWAGD59bAwdD";
    protected $TOKEN_SECRET    = "1qNkkr8254HRcL1m9gfPTp0MWeo";

    protected $API_HOST                = 'api.yelp.com';
    protected $DEFAULT_TERM            = 'dinner';
    protected $DEFAULT_LOCATION        = 'San Francisco, CA';
    protected $DEFAULT_CATEGORY_FILTER = "food";
    protected $DEFAULT_LAT_LON         = NULL;
    protected $RADIUS_FILTER           = 0;
    protected $SEARCH_LIMIT            = 5;
    protected $OFFSET                  = 0;
    protected $SEARCH_PATH             = '/v2/search/';
    protected $BUSINESS_PATH           = '/v2/business/';
    protected $BUSINESS_ID             = '/v2/business/';

    function request($host, $path) {
        $unsigned_url = "http://" . $host . $path;

        // Token object built using the OAuth library
        $token = new OAuthToken($this->TOKEN, $this->TOKEN_SECRET);

        // Consumer object built using the OAuth library
        $consumer = new OAuthConsumer($this->CONSUMER_KEY, $this->CONSUMER_SECRET);

        // Yelp uses HMAC SHA1 encoding
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

        $oauthrequest = OAuthRequest::from_consumer_and_token(
            $consumer,
            $token,
            'GET',
            $unsigned_url
        );

        // Sign the request
        $oauthrequest->sign_request($signature_method, $consumer, $token);

        // Get the signed URL
        $signed_url = $oauthrequest->to_url();

        // Send Yelp API Call
        $ch = curl_init($signed_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    /**
     * Query the Search API by a search term and location
     *
     * @param    $term        The search term passed to the API
     * @param    $location    The search location passed to the API
     *
     * @return   The JSON response from the request
     */
    function search($term, $location) {
        $url_params = [];

        //$url_params['term']     = $term ?: $this->DEFAULT_TERM;
        $url_params['category_filter'] = $term ?: $this->DEFAULT_CATEGORY_FILTER;
        $url_params['location']        = $location ?: $this->DEFAULT_LOCATION;

        if ($this->DEFAULT_LOCATION != 'San Francisco, CA' && $this->DEFAULT_LAT_LON != NULL) {
            $url_params['cll'] = $this->DEFAULT_LAT_LON;
        }

        if ($this->RADIUS_FILTER > 0) {
            $url_params['radius_filter'] = $this->RADIUS_FILTER;
        }

        $url_params['limit']       = $this->SEARCH_LIMIT;
        $url_params['business_id'] = $this->SEARCH_LIMIT;
        $url_params['offset']      = $this->OFFSET;
        $search_path               = $this->SEARCH_PATH . "?" . http_build_query($url_params);

        return $this->request($this->API_HOST, $search_path);
    }

    /**
     * Query the Business API by business_id
     *
     * @param    $business_id    The ID of the business to query
     *
     * @return   The JSON response from the request
     */
    function get_business($business_id) {
        $business_path = $this->BUSINESS_PATH . $business_id;

        return $this->request($this->API_HOST, $business_path);
    }

    /**
     * Queries the API by the input values from the user
     *
     * @param    $term        The search term to query
     * @param    $location    The location of the business to query
     */
    function query_api($term, $location) {
        return $response = json_decode($this->search($term, $location));


        /*if (@$response->error) {
            return $response->error;
        }

        foreach($response->businesses as $r){
            echo $r->id."<br/>";
        }

        $business_id = $response->businesses[0]->id;

        $response = $this->get_business($business_id);
        echo "<pre/>";
        print_r($response);
        die;*/
    }

    function searchBusiness($businessId) {
        $search_path = $this->BUSINESS_PATH . $businessId;

        return $this->request($this->API_HOST, $search_path);
    }

    public function index(Request $request) {

        $term           = $request->get('term');
        $categoryFilter = $request->get('category_filter');
        $offset         = $request->get('offset');
        $location       = $request->get('location');
        $limit          = $request->get('limit');
        $radius         = $request->get("radius");
        $lat            = $request->get("lat");
        $lon            = $request->get("lon");

        if ($term && $term != "") {
            $this->DEFAULT_TERM = $term;
        }

        if ($categoryFilter) {
            $this->DEFAULT_CATEGORY_FILTER = $categoryFilter;
        }

        if ($location) {
            $this->DEFAULT_LOCATION = $location;
        }

        if ($limit) {
            $this->SEARCH_LIMIT = $limit;
        }

        if ($offset) {
            $this->OFFSET = $offset;
        }

        if ($radius) {
            $this->RADIUS_FILTER = $radius;
        }

        if ($lat && $lon) {
            $this->DEFAULT_LAT_LON = $lat . "," . $lon;
        }

        $data = $this->query_api($term, $location);
        //header('Content-Type: application/json');
        //echo "<pre/>";
        return $data;
        //print_r(json_encode($data));
    }

    public function storeSearch($input = []) {
        $term           = @$input['term'];
        $categoryFilter = @$input['category_filter'];
        $offset         = @$input['offset'];
        $location       = @$input['location'];
        $limit          = @$input['limit'];
        $radius         = @$input["radius"];
        $lat            = @$input["lat"];
        $lon            = @$input["lon"];

        if ($term) {
            $this->DEFAULT_TERM = $term;
        }

        if ($categoryFilter) {
            $this->DEFAULT_CATEGORY_FILTER = $categoryFilter;
        }

        if ($location) {
            $this->DEFAULT_LOCATION = $location;
        }

        if ($limit) {
            $this->SEARCH_LIMIT = $limit;
        }

        if ($offset) {
            $this->OFFSET = $offset;
        }

        if ($radius) {
            $this->RADIUS_FILTER = $radius;
        }

        if ($lat && $lon) {
            $this->DEFAULT_LAT_LON = $lat . "," . $lon;
        }

        $data = $this->query_api($term, $location);

        return $data;
    }
}