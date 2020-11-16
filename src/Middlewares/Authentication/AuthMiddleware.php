<?php

/*
*   Author: Juan Marcos Vallejo 
*   Date: 2020/10/11
*   Description: Authentication middleware, used to filter user route access by UserRole.
*/

namespace Middlewares\Authentication;

use Components\GenericResponse;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Components\Token;
use Enum\UserRole;
use Slim\Psr7\Response;

class AuthMiddleware
{
    public $roleArray;

    public function __construct($roleArray)
    {
        $this->roleArray = $roleArray;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        /* It should be obtained from the token payload. */
        $token = $request->getHeaders()['token'] ?? "";
        $inputRole =  Token::getRole($token) ?? "guest";
        $valid = in_array($inputRole, $this->roleArray);
         
        /* Invalid credentials, returns Unauthorized. */
        if (!$valid) {
            $response = new Response();
            $response->getBody()->write(GenericResponse::obtain(false, "No posee privilegios suficientes.", $inputRole));
            return $response->withStatus(401);
        }

        /* Valid credentials, returns the content. */
        $response = $handler->handle($request);
        $existingContent = (string) $response->getBody();
        $resp = new Response();
        $resp->getBody()->write($existingContent);

        return $resp;
    }
}
