<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class XMLTransactionHander{
	public function __construct($params)
	{
	    $this->URL = $params[0];
	    $this->XMLRequest = $params[1];
	    $this->XMLResponseRaw = $params[2];
	    $this->XPath = $params[3];
	    $this->errno = $params[4];
	}

    public function curlRequest(){
		$ch2 = curl_init();
		curl_setopt($ch2, CURLOPT_URL, $this->URL);
		curl_setopt($ch2, CURLOPT_TIMEOUT, 540);
		curl_setopt($ch2, CURLOPT_HEADER, 0);
		curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch2, CURLOPT_POST, 1);
		curl_setopt($ch2, CURLOPT_POSTFIELDS, $this->XMLRequest);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch2,CURLOPT_SSL_CIPHER_LIST, 'TLSv1');
		curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
	 
	 	$httpHeader2 = array(
			"Content-Type: text/xml; charset=UTF-8",
			"Content-Encoding: UTF-8"
		);
	
		$xml = curl_exec($ch2);
		$this->errno=curl_getinfo($ch2, CURLINFO_HTTP_CODE );

		curl_close($ch2);
        return($xml);
    }

    function executeRequest($URL, $request) {
        $this->URL = $URL;
        $this->XMLRequest = $request;
        $this->getFeed();

        if( $this->errno == 200 ) {
            $inputDoc = new DOMDocument();
            if(isset($this->XMLResponseRaw) && ($this->XMLResponseRaw != ''))
            	$inputDoc->loadXML($this->XMLResponseRaw);
            else
            	$inputDoc = NULL;

            return $inputDoc;
        }
        else {
            return NULL;
        }
    } 

    function getFeed() {
        $rawData = $this->curlRequest();

        if ($rawData != -1) {
            $this->XMLResponseRaw = $rawData;
        }
    } 
}

?>