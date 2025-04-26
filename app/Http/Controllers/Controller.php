<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

#[
    OA\Info(
        version: '1.0.0',
        title: 'L5 OpenApi',
        description: 'L5 Swagger OpenApi description',
    ),
    OA\Server(url: 'http://localhost:8000/api', description: 'Localhost server'),
    OA\Server(url: 'http://127.0.0.1:8000/api', description: 'Local server'),
    OA\Server(url: 'https://api.example.com'),
    OA\SecurityScheme(securityScheme: 'Bearer',type: 'http', name:'Authorization', in:'header' , scheme: 'bearer'),
]
abstract class Controller
{
    //
}
