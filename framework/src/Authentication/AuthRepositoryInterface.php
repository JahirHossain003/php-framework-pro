<?php

namespace Jahir\Framework\Authentication;

interface AuthRepositoryInterface
{
    public function findByUsername(string $username): ?AuthUserInterface;
}