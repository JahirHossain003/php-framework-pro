<?php declare(strict_types=1);

namespace Jahir\Framework\Http;

use Jahir\Framework\Session\SessionInterface;

class Request
{
    private SessionInterface $session;

    private mixed $handler;

    private array $handlerArgs;

    public function __construct(
        public readonly array $getParams,
        public readonly array $postParams,
        public readonly array $cookies,
        public readonly array $files,
        public readonly array $server
    ){

    }

    public static function createFromGlobals(): static {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getPathInfo(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    public function input($key): mixed
    {
        return $this->postParams[$key];
    }

    /**
     * @return mixed
     */
    public function getHandler(): mixed
    {
        return $this->handler;
    }

    /**
     * @param mixed $handler
     */
    public function setHandler(mixed $handler): void
    {
        $this->handler = $handler;
    }

    /**
     * @return array
     */
    public function getHandlerArgs(): array
    {
        return $this->handlerArgs;
    }

    /**
     * @param array $handlerArgs
     */
    public function setHandlerArgs(array $handlerArgs): void
    {
        $this->handlerArgs = $handlerArgs;
    }
}