<?

class Cloudapp {

    private $ch;

    public function __construct($name, $password) {
        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
         
        curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($this->ch, CURLOPT_USERPWD, $name . ':' . $password);
    }

    public function getListItems($params = array('page' => 1, 'perPage' => 10, 'type' => null, 'deleted' => null, 'source' => null)) {
        $params = $this->arrayToUrlParams($params);
        $url = "http://my.cl.ly/items?" . $params;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $output = curl_exec($this->ch);
        return json_decode($output);
    }

    public function getAccountStats() {
        curl_setopt($this->ch, CURLOPT_URL, 'http://my.cl.ly/account/stats');
        $output = curl_exec($this->ch);
        return json_decode($output);
    }

    public function uploadFile($file) {
        $responce = $this->uploadRequest();
        if ($responce->uploads_remaining > 0) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_URL, $responce->url);

            $params = array(
                'key' => $responce->params->key,
                'AWSAccessKeyId' => $responce->params->AWSAccessKeyId,
                'acl' => $responce->params->acl,
                'success_action_redirect' => $responce->params->success_action_redirect,
                'policy' => $responce->params->policy,
                'signature' => $responce->params->signature,
                'file' => "@" . $file
            );

            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            $output = curl_exec($curl);
            $location = $this->getLocation($output);
            echo $location;
           $output = $this->confirmFile($location);
           
           
           echo $output;
            return json_decode($output);
        }
        return false;
    }

    private function arrayToUrlParams($params) {
        $newParams = array();
        foreach ($params as $name => $value) {
            if ($value !== null) {
                $newParams[] = $name . '=' . urlencode($value);
            }
        }
        return implode('&', $newParams);
    }

    private function confirmFile($location) {
        
        curl_setopt($this->ch, CURLOPT_URL, $location);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        $output = curl_exec($this->ch);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        echo $output;
        return json_decode($output);
    }

    private function getLocation($headers) {
        $headers = explode("\n", $headers);
        return str_replace('Location: ', '', $headers[7]);
    }

    private function uploadRequest() {
        curl_setopt($this->ch, CURLOPT_URL, 'http://my.cl.ly/items/new?item[private]=false');
        $output = curl_exec($this->ch);
        return json_decode($output);
    }

}
