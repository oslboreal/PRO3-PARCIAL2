<?php

namespace Controllers;

use Components\Token;
use Models\Materia;
use Models\User;
use Models\Inscripcion;
use Components\GenericResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InscripcionController
{
    public function addOne(Request $request, Response $response, $args)
    {
        try {
            $idMateria = $args['idMateria'] ?? '';
            $materia = Materia::where('id', $idMateria)->first();
            $idAlumno = Token::getId($token = $request->getHeaders()['token'] ?? "");

            if (empty($idMateria)) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear inscripcion, idMateria no puede ser vacio."));
                $response->withStatus(400);
            } else if (!$materia) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear una inscripcion, materia inexistente."));
                $response->withStatus(400);
            } else {

                $count = count(Inscripcion::where('id_materia', $idMateria)->get());

                if (Inscripcion::where('id_materia', $idMateria)->where('id_alumno', $idAlumno)->exists()) {
                    $response->getBody()->write(GenericResponse::obtain(true, "Error al crear una inscripcion, la inscripción (alumno-materia) ya fue realizada)."));
                    $response->withStatus(400);
                } else if ($materia->cupos > $count) {
                    $inscrip = new Inscripcion();
                    $inscrip->id = 0;
                    $inscrip->id_materia = $idMateria;
                    $inscrip->id_alumno = $idAlumno;
                    $inscrip->save();
                    $response->getBody()->write(GenericResponse::obtain(true, "Materia agregada correctamente.", $inscrip));
                } else {
                    $response->getBody()->write(GenericResponse::obtain(true, "Error al crear una inscripcion, no hay mas cupos."));
                    $response->withStatus(400);
                }
            }
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de crear una inscripcion", null));
        }

        return $response;
    }

    public function getAll(Request $request, Response $response, $args)
    {
        try {
            $idMateria = $args['idMateria'] ?? '';
            $materia = Materia::where('id', $idMateria)->first();

            if (empty($idMateria)) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al obtener, idMateria no puede ser vacio."));
                $response->withStatus(400);
            } else if (!$materia) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al obtener, materia inexistente."));
                $response->withStatus(400);
            } else {

                $all = User::select('users.id', 'users.nombre', 'users.email', 'users.area')
                                    ->join('inscripciones', 'users.id', '=', 'inscripciones.id_alumno')
                                    ->where([['id_materia', $idMateria]])
                                    ->get();

                    $response->getBody()->write(GenericResponse::obtain(true, "", $all));
          
            }
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de traer los alumnos inscriptos", null));
        }

        return $response;
    }
}
