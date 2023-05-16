<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Repository\UserDataMapper;
use Jahir\Framework\Authentication\SessionAuthentication;
use Jahir\Framework\Controller\AbstractController;
use Jahir\Framework\Http\RedirectResponse;
use Jahir\Framework\Http\Response;

class RegistrationController extends AbstractController
{
    public function __construct(
        private UserDataMapper $dataMapper,
        private SessionAuthentication $authComponent
    )
    {
    }

    public function index(): Response
    {
        return $this->render('register.html.twig');
    }

    public function register(): Response
    {
        $form = new RegistrationForm($this->dataMapper);
        $form->setFields(
            $this->request->input('username'),
            $this->request->input('password')
        );

        if ($form->hasValidationErrors()) {
            $validationErrors = $form->getValidationErrors();
            foreach ($validationErrors as $error) {
                $this->request->getSession()->setFlash('error', $error);
            }

            return new RedirectResponse('/register');
        }

        $user = $form->save();

        if ($user instanceof User) {
            $this->request->getSession()->setFlash('success', 'User has been created successfully');
            $this->authComponent->login($user);
        }

        return new RedirectResponse("/dashboard");
    }
}