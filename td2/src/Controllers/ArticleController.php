<?php

namespace App\Controllers;

use App\Models\Article;
use App\Views\VueParticipant;
use Slim\Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class ArticleController
{
    private $container;
    private $htmlvars;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    function listArticles(Request $rq, Response $rs, array $args ): Response
    {
        $a = new Article();
        $a->nom= 'A12609' ;
        $a->descr= 'beau velo de course rouge' ;
        $a->tarif= 59.95;
        $a->insert();

        $list = Article::all();

        if (!is_null($list))
        {
            $vue = new VueParticipant([$list], $this->container);
            $this->htmlvars['basepath'] = $rq->getUri()->getPath();
            $rs->getBody()->write($vue->question1());
        }
        return $rs;
    }

}