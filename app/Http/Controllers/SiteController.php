<?php

namespace App\Http\Controllers;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SiteController extends Controller
{
    protected $template;

    public function __construct(Engine $templates)
    {
        $this->templates = $templates;
    }

    public function home(ServerRequestInterface $request, ResponseInterface $response, array $params = []): ResponseInterface
    {
        $response->getBody()->write(
            $this->templates->render('home')
        );

        return $response;
    }
}
