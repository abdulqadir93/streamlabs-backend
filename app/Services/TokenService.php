<?php

namespace App\Services;

use Firebase\JWT\JWT;
use stdClass;

class TokenService {
    private static $SECRET_KEY = "FF15A423D3DD23FB0F7AE07FDBB3CA16";

    public function encode(array $token): string {
        return JWT::encode(
            $token,
            TokenService::$SECRET_KEY
        );
    }

    public function decode(string $token) {
        return $this->convertStdClassToArray($this->_decode($token));
    }

    private function _decode(string $token): stdClass {
        return JWT::decode(
            $token,
            TokenService::$SECRET_KEY,
            array('HS256')
        );
    }

    private function convertStdClassToArray(stdClass $obj): array {
        return json_decode(json_encode($obj), true);
    }
}

?>