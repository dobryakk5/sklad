<?php

namespace Api\DomainServices;

use \Firebase\JWT\JWT;

/**
 * Сервис для работы с JWT токеном
 * Class JwtService
 * @package Api\DomainServices
 */
class JwtService
{
    const KEY = 'dfdsfgdgsdafg.fmnhnsd,gfnfdsfdsfdsdfhnglsdkgnmhkfdjsdfsdfdshnsgld.gnfdkjnmgsdlkndfkjgm\sd ;lknhfgdkjgm\SDLgjfdkgm ;LSDgjerakgm;sPOrjg845eigmoep5y89isd/kgn8oe540y4';
    const ALLOWED_ALGORITMS = array(
        'ES256',
        'HS256',
        'HS384',
    );

    /**
     * Генерирует jwt токен для заданных данных
     * @param $payload
     * @return string
     */
    public function generateToken($payload)
    {
        $token = JWT::encode($payload, self::KEY);
        return $token;
    }

    /**
     * Декодирует зашифрованные данные
     * @param $token
     * @return object
     */
    public function decodePayload($token)
    {
        $payload = JWT::decode($token, self::KEY, self::ALLOWED_ALGORITMS);
        return $payload;
    }

}