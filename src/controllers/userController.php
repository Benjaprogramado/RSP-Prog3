<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;
use App\models\User;



class UserController
{
    public function getAll(Request $request, Response $response, $args)
    {
        // $rta = User::where('numero', '>', 100)
        // ->where('nombre', '=', 'Aguirre')
        // ->get();
        $rta = User::get();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }


    public function getOne(Request $request, Response $response, $args)
    {
        $rta = User::find($args['id']);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function addOne(Request $request, Response $response, $args)
    {
        $parsedBody = $request->getParsedBody();

        $User = new User;
        $User->email = $parsedBody["email"];
        $User->nombre = $parsedBody["nombre"];

        if (strlen($parsedBody["clave"]) >= 4) {
            $User->clave = $parsedBody["clave"];
        }else{
            echo"La clave no cumple con la longitud minima";
            die();
        }
        if($parsedBody["tipo"]=="cliente"||$parsedBody["tipo"]=="admin"){
            $User->tipo = $parsedBody["tipo"];
        }else{
            echo"El tipo ingresado es incorrecto";
            die();
        }

        $rta = $User->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }





    public function login(Request $request, Response $response)
    {
        $mail = $request->getParsedBody()['email'];
        $clave = $request->getParsedBody()['clave'];

        $token = self::generarToken($clave, $mail);

        if ($token != false) {
            $response->getBody()->write("Token generado con éxito!!\n" . json_encode($token));
        } else {
            $response->getBody()->write("\nError al generar token, datos inválidos");
        }

        return $response;
    }



    public function updateOne(Request $request, Response $response, $args)
    {
        $parsedBody = $request->getParsedBody();
        // var_dump($parsedBody);
        // die();

        $User = User::find($args['id']);

        $User->email = $parsedBody["email"];
        $User->nombre = $parsedBody["nombre"];
        $User->clave = $parsedBody["clave"];
        $User->tipo = $parsedBody["tipo"];


        $rta = $User->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }




    public function deleteOne(Request $request, Response $response, $args)
    {
        $User = User::find($args['id']);

        $rta = $User->delete();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }





    public static function generarToken($clave, $mail)
    {

        $usuario = User::where('clave', $clave)->where('email', $mail)->first();
        $payload = array();

        $token = false;
        if ($usuario != null) {
            $payload = array(
                "email" => $mail,
                "clave" => $clave,
                "id" => $usuario->id,
                "tipo" => $usuario->tipo
            );
            $token = JWT::encode($payload, 'segundo-parcial');
        } else {
            echo "Usuario no registrado!!";
        }
        return $token;
    }




    public static function verificarPermisos($token, $tipo)
    {
        $retorno = false;
        try {
            $payload = JWT::decode($token, "segundo-parcial", array('HS256'));

            foreach ($payload as $value) {
                if ($value == $tipo) {

                    $retorno = true;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
        return $retorno;
    }




    public static function ObtenerIdToken($token)
    {
        try {
            $payload = JWT::decode($token, "segundo-parcial", array('HS256'));
            // var_dump($payload);
            // die();
            foreach ($payload as $key => $value) {
                if ($key == 'id') {

                    return $value;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
    }




    public static function ObtenerTipoToken($token)
    {
        try {
            $payload = JWT::decode($token, "segundo-parcial", array('HS256'));
            foreach ($payload as $key => $value) {
                if ($key == 'tipo') {

                    return $value;
                }
            }
        } catch (\Throwable $th) {
            echo 'Excepcion:' . $th->getMessage();
        }
    }
}
