<?php

namespace Jahir\Framework\Template;

use Jahir\Framework\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(private SessionInterface $session, private string $templatePath) {

    }

    public function create(): Environment
    {
        $fileSystemLoader = new FilesystemLoader($this->templatePath);
        $twigEnvironment  = new Environment($fileSystemLoader, [
           'debug' => true,
           'cache' => false
        ]);

        $twigEnvironment->addExtension(new DebugExtension());
        $twigEnvironment->addFunction(new TwigFunction('session', [$this, 'getSession']));

        return $twigEnvironment;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }
}