<?php

/**
 * Library ini digunakan untuk validasi jwt dan generate jwt token dan validate jwt token + get data user_id di payload token
 * 
 * Author: Wildan M Zaki
 */
defined('BASEPATH') or exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Jwtauth
{

    protected $CI;

    protected $jwt_key;
    protected $daysValidToken;

    protected $algorithm = 'HS256';

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->jwt_key = $_ENV['JWT_KEY'] ?? 'NqkEytD0zHsECTL/9VncPhW/UhAll10lSbTYsx/U8rs=';
        $this->daysValidToken = $_ENV['JWT_EXPIRATION_DAYS'] ?? 7;
    }

    public function validate_token()
    {
        $auth = $this->CI->input->get_request_header('Authorization');
        $token = null;

        if ($auth && preg_match('/Bearer\s+(.*)$/i', $auth, $matches)) {
            $token = $matches[1];
        }

        if ($token) {
            $key = $this->jwt_key;

            try {
                $decoded_token = JWT::decode($token, new Key($key, $this->algorithm));

                if ($decoded_token->exp < time()) {
                    $this->sendJsonError('Token telah kadaluwarsa', 401);
                }

                $user_id = $decoded_token->user_id;
                $this->CI->session->set_userdata('user_id', $user_id);
            } catch (Exception $e) {
                $this->sendJsonError('Token invalid', 401);
            }
        } else {
            $this->sendJsonError('Tidak ada token ditemukan', 401);
        }
    }

    /**
     * Sertakan user_id => X
     * di additional payload, maka nanti function validate_token di atas bisa mendapatkan user_id dan menyimpannya sementara di session
     * example usage di modules/api/controllers/Auth.php, login method
     */
    public function generateToken($additionalPayloads = [])
    {
        $current = time();
        $days = $this->daysValidToken;
        $exp = $current + ($days * 24 * 60 * 60);

        $baseUrl = 'https://vetencode.com';
        $payload = [
            'iss' => $baseUrl,
            'aud' => $baseUrl,
            'iat' => $current, // Set issued at to current time
            'exp' => $exp,     // Set expiration time
            'nbf' => $current, // Set not before to current time
        ];

        foreach ($additionalPayloads as $key => $value) {
            $payload[$key] = $value;
        }

        $jwt = JWT::encode($payload, $this->jwt_key, $this->algorithm);
        return $jwt;
    }

    private function sendJsonError($message, $statusCode)
    {
        $response = array(
            'success' => false,
            'message' => $message
        );

        http_response_code(401);
        echo json_encode($response);
        // Stop the flow of the program
        exit;
    }
}
