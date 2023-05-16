<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserDataMapper;

class RegistrationForm
{
    public function __construct(private UserDataMapper $userDataMapper)
    {

    }

    private string $username;
    private string $password;

    private array $errors = [];

    public function setFields(string $username, string $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function save(): User
    {
        $user = User::create($this->username, $this->password);

        $this->userDataMapper->save($user);

        return $user;
    }

    public function hasValidationErrors(): bool
    {
        return count($this->getValidationErrors()) > 0;
    }
    public function getValidationErrors(): array
    {
        if (!empty($this->errors)) {
            return $this->errors;
        }

        // username length
        if (strlen($this->username) < 5 || strlen($this->username) > 20) {
            $this->errors[] = 'Username must be between 5 and 20 characters';
        }

        // username char type
        if (!preg_match('/^\w+$/', $this->username)) {
            $this->errors[] = 'Username can only consist of word characters without spaces';
        }

        // password length
        if (strlen($this->password) < 8) {
            $this->errors[] = 'Password must be at least 8 characters';
        }

        return $this->errors;

    }
}