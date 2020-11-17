<?php

namespace Controllers;

use Models\Materia;
use Models\Nota;
use Models\User;
use Components\Token;
use Components\GenericResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NotasController
{
    public function addOne(Request $request, Response $response, $args)
    {
        try {

            $idMateria = $args['idMateria'] ?? '';
            $idAlumno =  $request->getParsedBody()['idAlumno'] ?? '';
            $nota =  $request->getParsedBody()['nota'] ?? '';
            $materia = Materia::where('id', $idMateria)->first();
            $alumno = User::where('id', $idAlumno)->first();

            if (empty($nota) || !($nota >= 0 && $nota <= 10)) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear nota, la nota debe ser entre 0 y 10."));
                $response->withStatus(400);
            } else if (empty($idMateria)) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear nota, idMateria no puede ser vacio."));
                $response->withStatus(400);
            } else if (!$materia) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear una nota, materia inexistente."));
                $response->withStatus(400);
            } else if (!$alumno) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear una nota, alumno inexistente."));
                $response->withStatus(400);
            } else {

                $dbNota = new Nota();
                $dbNota->id = 0;
                $dbNota->id_alumno = 1;
                $dbNota->id_materia = 2;
                $dbNota->nota = 4;
                $dbNota->save();

                $response->getBody()->write(GenericResponse::obtain(true, "Nota creada correctamente.", $dbNota));
            }
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de crear una NOTA", null));
        }

        return $response;
    }

    public function getNotas(Request $request, Response $response, $args)
    {
        try {
            $idMateria = $args['idMateria'] ?? '';
            $materia = Materia::where('id', $idMateria)->exists();
            if (empty($idMateria)) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al obtener, idMateria no puede ser vacio."));
                $response->withStatus(400);
            } else if (!$materia) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al obtener, las notas, la materia no existe."));
                $response->withStatus(400);
            } else {
                $all = Nota::where('id_materia', $idMateria)->get();
                $response->getBody()->write(GenericResponse::obtain(true, "", $all));
            }
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de traer las notas", null));
        }

        return $response;
    }
}
