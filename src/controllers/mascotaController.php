<?php


namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;
use App\models\Mascota;

class MascotaController
{

    public function addOne(Request $request, Response $response, $args)
    {
        $parsedBody = $request->getParsedBody();

        $todos = Mascota::get();

        foreach ($todos as $value) {
            if($value->tipo == $parsedBody["tipo"]){
                echo"Error, el tipo ingresado ya existe";
                die();
            }
        }

        $Mascota = new Mascota;
        $Mascota->tipo = $parsedBody["tipo"];
        $Mascota->precio = $parsedBody["precio"];


        $rta = $Mascota->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function mostrarTipo($numTipo){
        if($numTipo==1){
            return "perro";
        }elseif($numTipo==2){
            return "gato";
        } elseif($numTipo==3){
            return "huron";
        }else{
            return "no definido";
        }

    }

    public static function asignarCosto($tipo){
        if($tipo=="perro"){
            return 1000 ;
        }elseif($tipo=="gato"){
            return 600;
        } elseif($tipo=="huron"){
            return 1200;
        }else{
            return "no definido";
        }

    }


}
