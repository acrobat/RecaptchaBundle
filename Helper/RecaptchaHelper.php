<?php

namespace Acrobat\Bundle\RecaptchaBundle\Helper;

/**
 * RecaptchaHelper
 *
 * Helper class for all logic for the recaptcha widget
 *
 * @author Jeroen Thora <jeroenthora@gmail.com>
 */
class RecaptchaHelper
{
    /**
     * Recaptcha base urls
     */
    const RECAPTCHA_API_URL      = 'www.google.com/recaptcha/api';
    const RECAPTCHA_API_AJAX_URL = 'www.google.com/recaptcha/api/js/recaptcha_ajax.js';
    const RECAPTCHA_VERIFY_HOST  = 'www.google.com';

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var boolean
     */
    private $ajax;

    /**
     * @var string
     */
    private $httpsMode;

    /**
     * @param string  $publicKey
     * @param string  $privateKey
     * @param string  $locale
     * @param boolean $enabled
     * @param boolean $ajax
     * @param string  $httpsMode
     */
    public function __construct($publicKey, $privateKey, $locale, $enabled, $ajax, $httpsMode)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->locale = $locale;
        $this->enabled = $enabled;
        $this->ajax = $ajax;
        $this->httpsMode = $httpsMode;
    }

    /**
     * Get the challenge javascript url
     *
     * @return string
     */
    public function getChallengeUrl()
    {
        return vsprintf('%s%s/challenge?k=%s', array(
            $this->getProtocol($this->httpsMode),
            self::RECAPTCHA_API_URL,
            $this->publicKey
        ));
    }

    /**
     * Get the noscript javascript url
     *
     * @return string
     */
    public function getNoScriptUrl()
    {
        return vsprintf('%s%s/noscript?k=%s', array(
            $this->getProtocol($this->httpsMode),
            self::RECAPTCHA_API_URL,
            $this->publicKey
        ));
    }

    /**
     * Get the ajax javascript url
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return vsprintf('%s%s', array(
            $this->getProtocol($this->httpsMode),
            self::RECAPTCHA_API_AJAX_URL,
        ));
    }

    /**
     * Get public key
     *
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Get private key
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Use ajax for recaptcha widget
     *
     * @return boolean
     */
    public function useAjax()
    {
        return $this->ajax;
    }

    /**
     * Is recaptcha widget enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Check the users recaptcha input
     *
     * @param string $remoteIp
     * @param string $challenge
     * @param string $response
     * @param array  $extraParams
     *
     * @return boolean
     */
    public function checkAnswer($remoteIp, $challenge, $response, $extraParams = array())
    {
        $postData = array_merge($extraParams, array(
            'privatekey' => $this->getPrivateKey(),
            'remoteip'   => $remoteIp,
            'challenge'  => $challenge,
            'response'   => $response
        ));

        $response = $this->httpPost(self::RECAPTCHA_VERIFY_HOST, '/recaptcha/api/verify', $postData);

        $answers = explode("\n", $response[1]);

        if (trim($answers[0]) == 'true') {
            return true;
        }

        return false;
    }

    /**
     * Perform HTTP POST to check recaptcha input
     *
     * @param string  $host
     * @param string  $path
     * @param array   $data
     * @param integer $port
     *
     * @throws \Exception
     * @return array
     */
    private function httpPost($host, $path, $data, $port = 80)
    {
        $req = $this->getQSEncode($data);

        $httpRequest  = "POST $path HTTP/1.0\r\n";
        $httpRequest .= "Host: $host\r\n";
        $httpRequest .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $httpRequest .= "Content-Length: ".strlen($req)."\r\n";
        $httpRequest .= "User-Agent: reCAPTCHA/PHP\r\n";
        $httpRequest .= "\r\n";
        $httpRequest .= $req;

        $response = null;
        try {
            $fs = fsockopen($host, $port, $errno, $errstr, 10);
        } catch(\Exception $e) {
            throw new \Exception('Could not open socket. Errorcode: ' . $errno . '. Error: ' . $errstr);
        }

        fwrite($fs, $httpRequest);

        while (!feof($fs)) {
            $response .= fgets($fs, 1160); // one TCP-IP packet
        }

        fclose($fs);

        $response = explode("\r\n\r\n", $response, 2);

        return $response;
    }

    /**
     * Encode query string parameters
     *
     * @param array $data
     *
     * @return string
     */
    private function getQSEncode($data)
    {
        $req = '';
        foreach ($data as $key => $value) {
            $req .= $key . '=' . urlencode(stripslashes($value)) . '&';
        }

        // Cut the last '&'
        return substr($req, 0, strlen($req) - 1);
    }

    private function getProtocol($httpsMode)
    {
        switch ($httpsMode) {
            case 'on':
                return 'https://';
            case 'off':
                return 'http://';
            case 'auto':
                return '//';
        }
    }
}
