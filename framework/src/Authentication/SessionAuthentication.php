<?php

namespace Jahir\Framework\Authentication;

use Jahir\Framework\Session\Session;
use Jahir\Framework\Session\SessionInterface;

class SessionAuthentication implements SessionAuthInterface
{
    private AuthUserInterface $user;

    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private SessionInterface $session
    )
    {
    }

    public function authenticate(string $username, string $password): bool
    {
        // query db for user using username
        $user = $this->authRepository->findByUsername($username);

        if (!$user) {
            return false;
        }

        // Does the hashed user pw match the hash of the attempted password
        if (password_verify($password, $user->getPassword())) {
            $this->login($user);
            return true;
        }

        // return false
        return false;

    }

    public function login(AuthUserInterface $user)
    {
        $this->session->start();

        $this->session->set(Session::AUTH_KEY, $user->getAuthId());

        $this->user = $user;
    }

    public function logout()
    {
        $this->session->remove(Session::AUTH_KEY);
    }

    public function getUser(): AuthUserInterface
    {
        return $this->user;
    }
}