<?php
namespace App\Controller;
use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Http\Response;

class DashboardController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('dashboard.html.twig');
    }
}