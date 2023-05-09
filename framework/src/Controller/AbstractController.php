<?php

namespace Jahir\Framework\Controller;

use Jahir\Framework\Http\Response;
use Psr\Container\ContainerInterface;
use Twig\Environment;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function render(string $template, array $params = [], Response $response = null): Response
    {
        /** @var Environment $twig */
        $twig = $this->container->get('twig');

        $content = $twig->render($template, $params);

        $response ??= new Response();

        $response->setContent($content);

        return $response;

    }
}