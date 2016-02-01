<?php
/**
 * Project : dCard
 * File : OAuthConsumer.php
 * Author : Abu Bakar Siddique
 * Email : absiddique.live@gmail.com
 * Date: 8/26/15 - 3:20 PM
 */

namespace App\Http\Lib\Yelp;


class OAuthConsumer {
    public $key;
    public $secret;

    function __construct($key, $secret, $callback_url = NULL) {
        $this->key          = $key;
        $this->secret       = $secret;
        $this->callback_url = $callback_url;
    }

    function __toString() {
        return "OAuthConsumer[key=$this->key,secret=$this->secret]";
    }
}