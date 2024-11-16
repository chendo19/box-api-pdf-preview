<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TokenController extends Controller
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $apiUrl;

    public function __construct()
    {
        $this->clientId = Config::get('box.client_id');
        $this->clientSecret = Config::get('box.client_secret');
        $this->redirectUri = Config::get('box.redirect_uri');
        $this->apiUrl = Config::get('box.api_url');
    }

    public function getCode()
    {   
        $ch = curl_init();

        $url = 'https://account.box.com/api/oauth2/authorize?response_type=code&client_id='.$this->clientId.'&redirect_uri='.$this->redirectUri;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $headerSize);
            return $body;
        }

        curl_close($ch);
    }

    public function getToken()
    {
        $code = 'aaaaaaaaaaaaaaaa';

        $postData = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];

        $ch = curl_init();

        $postFields = http_build_query($postData);

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $headerSize);
            return $body;
        }

        curl_close($ch);
    }

    public function getNewBoxApiToken()
    {
        $refresh_token = $this->getCurrentRefreshToken();

        $ch = curl_init();

        $postData = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token'
        ];

        $postFields = http_build_query($postData);

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return $this->json(['error' => curl_error($ch)]);
        } else {
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $body = substr($response, $headerSize);
            $token = json_decode($body, true);

            try {
                $this->saveGeneratedRefreshToken($token['refresh_token']);
                return response()->json($token['access_token']);
            } catch (\Exception $ex) {
                return response()->json(['status' => 'failed', 'message' => $ex->getMessage()]);
            }
        }

        curl_close($ch);
    }

    private function getCurrentRefreshToken(): string
    {
        $current = Token::first();
        return $current->refresh_token;
    }

    private function saveGeneratedRefreshToken(string $token): void
    {
        $current = Token::first();
        $current->update(['refresh_token' =>  $token]);
    }
}
