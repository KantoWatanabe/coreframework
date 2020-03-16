<?php
namespace App\Core;

use App\Core\Log;

class HttpClient
{
    /**
     * @param string $url
     * @param array $params
     * @param array $headers
     * @param string|null $userpwd
     * @return array
     */
    public function get($url, $params = [], $headers = [], $userpwd = null)
    {
        return $this->communicate('GET', $url, $params, $headers, $userpwd);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $headers
     * @param string|null $userpwd
     * @return array
     */
    public function post($url, $params = [], $headers = [], $userpwd = null)
    {
        return $this->communicate('POST', $url, $params, $headers, $userpwd);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $params
     * @param array $headers
     * @param string|null $userpwd
     * @return array
     */
    private function communicate($method, $url, $params = [], $headers = [], $userpwd = null)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if ($method === 'GET') {
            curl_setopt($curl, CURLOPT_URL, $url . (strpos($url, '?') === false ? '?' : '&') . http_build_query($params));
        } else if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_URL, $url);
            if (in_array('Content-Type: application/json', $headers)) {
                $data = json_encode($params);
            } else {
                $data = http_build_query($params);
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        if ($userpwd !== null) {
            curl_setopt($curl, CURLOPT_USERPWD, $userpwd);
        }

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $total_time = curl_getinfo($curl, CURLINFO_TOTAL_TIME);

        curl_close($curl);

        Log::debug(sprintf('[%s][%s][%ssec]', $url, $http_code, $total_time));
        $result = json_decode($response, true);
        return $result;
    }
}
