<?php

namespace Controllers;

use Models\User;
use Components\PassManager;
use Components\GenericResponse;
use DateTime;
use Enum\UserRole;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController
{
    public function getAll(Request $request, Response $response, $args)
    {
        try {
            $users = User::all([
                'id',
                'email',
                'nombre',
                'area',
                'created_at',
                'updated_at'
            ]);

            $response->getBody()->write(GenericResponse::obtain(true, "", $users));
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de obtener los usuarios", null));
            $response->withStatus(500);
        }

        return $response;
    }

    public function deleteOne(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'] ?? '';

            if (empty($id)) {
                $response->getBody()->write(GenericResponse::obtain(false, "Error al borrar un usuario, el campo id es obligatorio.", null));
                $response->withStatus(401);
            } else {
                $user = User::where('id', $id)->first();

                if ($user) {
                    $user->delete();
                    $response->getBody()->write(GenericResponse::obtain(true, "Usuario borrado correctamente.", null));
                } else {
                    $response->getBody()->write(GenericResponse::obtain(true, "El usuario especificado es inexistente.", null));
                }
            }
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de obtener los usuarios", null));
            $response->withStatus(500);
        }

        return $response;
    }

    public function updateOne(Request $request, Response $response, $args)
    {
        try {
            $id = $args['id'] ?? '';

            if (empty($id)) {
                $response->getBody()->write(GenericResponse::obtain(false, "Error al modificar un usuario, el campo id es obligatorio.", null));
                $response->withStatus(401);
            } else {

                $email = $request->getParsedBody()['email'] ?? null;
                $password = $request->getParsedBody()['password'] ?? null;
                $area = $request->getParsedBody()['area'] ?? null;

                if ($area != null && !UserRole::IsValidArea($area)) {
                    $response->getBody()->write(GenericResponse::obtain(true, "Error al modificar un usuario, area invalida.", $area));
                    $response->withStatus(400);
                } else {
                    $user = User::where('id', $id)->first();

                    if (!empty($email))
                        $user->email = $email;

                    if (!empty($password))
                        $user->hash = PassManager::Hash($password);

                    if (!empty($area))
                        $user->area = $area;

                    $user->save();
                    $user->hash = null;

                    $response->getBody()->write(GenericResponse::obtain(true, "", $user));
                }
            }
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de obtener los usuarios", null));
            $response->withStatus(500);
        }

        return $response;
    }

    public function getOne(Request $request, Response $response, $args)
    {
        try {
            $user = User::where('id', $args['id'])->first([
                'id',
                'email',
                'area',
                'created_at',
                'updated_at'
            ]);

            $response->getBody()->write(GenericResponse::obtain(true, "", $user));
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de obtener el usuario", null));
        }

        return $response;
    }

    public function getMinutes(Request $request, Response $response, $args)
    {
        try {

            $user = User::where('id', $args['id'])->first([
                'id',
                'email',
                'area',
                'created_at',
                'updated_at'
            ]);

            $createdAt = new DateTime($user->created_at);
            $updatedAt = new DateTime($user->updated_at);

            $diff = $updatedAt->getTimestamp() - $createdAt->getTimestamp();

            $response->getBody()->write(GenericResponse::obtain(true, "", $diff / 60));
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de obtener el usuario", null));
        }

        return $response;
    }


    public function addOne(Request $request, Response $response, $args)
    {
        try {

            $nombre = $request->getParsedBody()['nombre'] ?? '';
            $password = $request->getParsedBody()['clave'] ?? '';
            $rol = $request->getParsedBody()['tipo'] ?? '';
            $email = $request->getParsedBody()['email'] ?? '';
            $area = UserRole::getVal($rol);

            if (!$nombre || User::where('nombre', $nombre)->exists()) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear un usuario, nombre inválido o existente.", $area));
                $response->withStatus(400);
            } else if (strpos($nombre, ' ')) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear un usuario, nombre inválido, contiene espacios..", $area));
                $response->withStatus(400);
            } else if (strlen($password) < 4) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear un usuario, clave debil..", $area));
                $response->withStatus(400);
            } else if (User::where('email', '=', $email)->exists()) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear un usuario, el usuario ya existe.", $area));
                $response->withStatus(400);
            } else if (!UserRole::IsValidArea($area)) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear un usuario, area invalida.", $area));
                $response->withStatus(400);
            } else if (empty($email) || empty($password) || empty($area)) {
                $response->getBody()->write(GenericResponse::obtain(true, "Error al crear un usuario, los datos ingresados son inválidos."));
                $response->withStatus(400);
            } else {
                $user = new User;
                $user->id = 0;
                $user->email = $email;
                $user->nombre = $nombre;
                $user->hash = PassManager::Hash($password);
                $user->area = $area;
                $user->save();
                $user->hash = null;
                $response->getBody()->write(GenericResponse::obtain(true, "Usuario agregado correctamente.", $user));
            }
        } catch (\Exception $e) {
            $response->getBody()->write(GenericResponse::obtain(false, "Error a la hora de crear un nuevo usuario", null));
        }

        return $response;
    }
}
