<?php declare(strict_types=1);

namespace Jahir\Framework\Http;

use Jahir\Framework\Http\Exception\HttpException;
use Jahir\Framework\Http\Middleware\RequestHandlerInterface;
use Psr\Container\ContainerInterface;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private ContainerInterface $container,
        private RequestHandlerInterface $handler
    )
    {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {
        try {
            $response = $this->handler->handle($request);
        } catch (\Exception $exception) {
            $response = $this->createExceptionResponse($exception);
        }

        return $response;
    }

    /**
     * @throws \Exception $exception
     */
    private function createExceptionResponse(\Exception $exception): Response
    {
        if (in_array($this->appEnv, ['dev', 'test'])) {
            throw $exception;
        }

        if ($exception instanceof HttpException) {
            return new Response($exception->getMessage(), $exception->getCode());
        }

        return new Response($exception->getMessage(), Response::SERVER_ERROR);
    }
}