<?php

namespace App\Controller;


use Jahir\Framework\Authentication\SessionAuthentication;
use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Http\RedirectResponse;
use Jahir\Framework\Http\Response;

class LoginController extends AbstractController
{
    public function __construct(private SessionAuthentication $authComponent)
    {
    }

    public function index(): Response
    {
        return $this->render('login.html.twig');
    }

    public function login(): Response
    {
        // Attempt to authenticate the user using a security component (bool)
        // create a session for the user

        $userIsAuthenticated = $this->authComponent->authenticate(
          $this->request->input('username'),
          $this->request->input('password')
        );

        if (!$userIsAuthenticated) {
            $this->request->getSession()->setFlash('error', 'Bad Credentials');

            return new RedirectResponse('/login');
        }
        // If successful, retrieve the user
        $user = $this->authComponent->getUser();

        $this->request->getSession()->setFlash('success', 'You are successfully logged in');

        // Redirect the user to intended location
        return new RedirectResponse('/dashboard');
    }

    public function logout(): Response
    {
        $this->authComponent->logout();
        $this->request->getSession()->setFlash('success', 'You are successfully logged out');
        return new RedirectResponse('/login');
    }
}