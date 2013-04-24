<?

class Cloudapp {

    private $ch;
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->ch = curl_init();
        $this->username = $username;
        $this->password = $password;
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($this->ch, CURLOPT_USERPWD, $username . ':' . $password);
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
            curl_setopt($curl, CURLOPT_URL, $responce->url);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POST, 1);

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
            if ($output === false) {
                echo 'Ошибка curl: ' . curl_error($curl);
            }
            $location = $this->getLocation($output);
            $output = $this->confirmFile($location);
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, trim($location));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        $output = curl_exec($ch);
        return $output;
    }

    private function getLocation($headers) {
        $headers = explode("\n", $headers);
        $url = str_replace('Location: ', '', $headers['7']);
        return trim($url);
    }

    private function uploadRequest() {
        curl_setopt($this->ch, CURLOPT_URL, 'http://my.cl.ly/items/new');
        $output = curl_exec($this->ch);
        return json_decode($output);
    }

    public function getChangeDefaultSecurity() {
        return $this->changeDefaultSecurity;
    }

    public function getChangeEmail() {
        return $this->changeEmail;
    }

    public function getChangePassword() {
        return $this->changePassword;
    }

    public function getForgotPassword() {
        return $this->forgotPassword;
    }

    public function getRegister() {
        return $this->register;
    }

    public function getSetCustomDomain() {
        return $this->setCustomDomain;
    }

    public function getViewAccountDetails() {
        curl_setopt($this->ch, CURLOPT_URL, 'http://my.cl.ly/account');
        $output = curl_exec($this->ch);
        return json_decode($output);
    }

    public function getViewAccountStats() {
        return $this->viewAccountStats;
    }

    public function getBookmarkLink() {
        return $this->bookmarkLink;
    }

    public function getBookmarkMultipleLinks() {
        return $this->bookmarkMultipleLinks;
    }

    public function getChangeSecurityofItem() {
        return $this->changeSecurityofItem;
    }

    public function getDeleteItem() {
        return $this->deleteItem;
    }

    public function getListItemsbySource() {
        return $this->listItemsbySource;
    }

    public function getRecoverDeletedItem() {
        return $this->recoverDeletedItem;
    }

    public function getRenameItem() {
        return $this->renameItem;
    }

    public function getStreamItems() {
        return $this->streamItems;
    }

    public function getUploadFile() {
        return $this->uploadFile;
    }

    public function getUploadFileWithSpecificPrivacy() {
        return $this->uploadFileWithSpecificPrivacy;
    }

    public function getViewDomainDetails() {
        return $this->viewDomainDetails;
    }

    public function getViewItem($itemUrl) {
        curl_setopt($this->ch, CURLOPT_URL, $itemUrl);
        $output = curl_exec($this->ch);
        return json_decode($output);
    }

}
