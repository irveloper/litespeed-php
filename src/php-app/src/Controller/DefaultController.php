<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{



    public function index(int $max=50): Response
    {
        $number = random_int(0, $max);

        return $this->render('index/index.html.twig');
    }
}
