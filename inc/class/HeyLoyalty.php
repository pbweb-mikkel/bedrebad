<?php

require_once 'class-pb-log.php';

class HeyLoyalty {
    private $key;
    private $secret;
    private $url;
    private $list;

	/**
	 * ActiveCampaign constructor.
	 *
	 * @param string $key
	 * @param string $url
	 */
	public function __construct() {
		$this->key = '1t2d92KWCEj0Qb43';
		$this->secret = 'unK639yHoHLunSLYMA0DpIDmAJnU38EJ';
		$this->url = 'https://api.heyloyalty.com/loyalty/v1/';
        $this->list = 20665;
	}


	public function add_contact($email, $name){
		if(!$email){
			return false;
		}

		$firstname = '';
		$lastname = '';


		if($name){
			$name_parts = explode(' ', $name);
			$firstname = array_shift($name_parts);
			if($name_parts){
				$lastname = implode(' ', $name_parts);
			}
		}

		$body = array(
            'email' => $email,
            'firstname' => $firstname,
            'lastname' => $lastname,
		);

        $curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url."lists/".$this->list."/members",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS =>json_encode($body),
			CURLOPT_HTTPHEADER => $this->generate_headers()
		));

		$response = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

		if($response){
			$response = json_decode($response, true);
		}

		$body['http_response_code'] = $code;
		$body['id'] = $response['id'];

		if($code == 201 || $code == 200){
            new PB_Log('heyloyalty_add_member_success', $body, $response);
		}else{

            if($response['error'] == 'member_exists'){
                $code = 200;
            }
            new PB_Log('heyloyalty_add_member_failure', $body, $response);
		}

		return $code;
	}

    private function generate_headers(){
        $requestTimestamp = gmdate("D, d M Y H:i:s") . ' GMT';
        $requestSignature = base64_encode(hash_hmac('sha256', $requestTimestamp, $this->secret));
        $headers = [
            'X-Request-Timestamp: ' . $requestTimestamp,
            'Authorization: ' . 'Basic ' . base64_encode($this->key .':'. $requestSignature),
            "Content-Type: application/json",
        ];
        return $headers;
    }

}