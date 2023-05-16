<?php

namespace Jahir\Framework\Authentication;

interface AuthUserInterface
{
    public function getUsername(): string;
    public function getPassword(): string;
    public function getAuthId(): int|string;
}