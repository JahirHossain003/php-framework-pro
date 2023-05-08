<?php declare(strict_types=1);

namespace Jahir\Framework\Http;

use Jahir\Framework\Http\Exception\HttpException;
use Jahir\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(private RouterInterface $router, private ContainerInterface $container)
    {
    }

    public function handle(Request $request): Response
    {
        try {
            [$routeHandler, $vars] = $this->router->dispatch($request, $this->container);
            $response = call_user_func_array($routeHandler, $vars);
        } catch (HttpException $exception) {
            $response = new Response($exception->getMessage(), $exception->getStatus());
        } catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 500);
        }

        return $response;
    }
}