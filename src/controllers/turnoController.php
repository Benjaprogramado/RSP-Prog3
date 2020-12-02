<?php


namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;
use App\models\Turno;
use App\Controllers\MascotaController;
use App\models\User;

class TurnoController
{

    public function addOne(Request $request, Response $response, $args)
    {
        $parsedBody = $request->getParsedBody();

        $token = getallheaders()['token'];
        $id = UserController::ObtenerIdToken($token);

        $Turno = new Turno;
        $Turno->id_cliente = $id;
        $Turno->tipo = $parsedBody["tipo"];
        $Turno->fecha = $parsedBody["fecha"];
        $Turno->estado = "pendiente";

        $rta = $Turno->save();

        $response->getBody()->write(json_encode($rta));
        return $response;
    }



    public function atenderTurnos(Request $request, Response $response, $args)
    {

        $turno = Turno::find($args['idTurno']);
        $turno->estado = "finalizado";
        $rta = $turno->save();
        echo "El turno ha sido finalizado\n\n";
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function pagosPendientes(Request $request, Response $response, $args)
    {

        $token = getallheaders()['token'];
        $idUser = UserController::ObtenerIdToken($token);

        $facturas=[];
        $turnos = Turno::get();
        foreach ($turnos as $value) {
            if($value->id_cliente==$idUser){
                // array_push($facturas, MascotaController::asignarCosto($value->tipo);

            }
        }
    }




}
