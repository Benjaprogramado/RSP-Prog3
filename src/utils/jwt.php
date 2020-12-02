<?PHP

namespace App\utils;

use Exception;
use \Firebase\JWT\JWT;


class Token
{
    public static $key = 'segundoparcial';


    public static function generarToken($payload)
    {
        try {
            $token = JWT::encode($payload, self::$key);
            return $token;
        } catch (Exception $e) {
            echo ("Error al generar token!!");
            return false;
        }
    }

    public static function decifrarToken($token)
    {
        try {
            return JWT::decode($token, self::$key, array('HS256'));
        } catch (Exception $e) {
            echo ("Error al decifrar token!!");
            return false;
        }
    }

}
