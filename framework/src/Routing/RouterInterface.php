<?php declare(strict_types=1);

namespace Jahir\Framework\Routing;

use Jahir\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request): array;

    public function setRoutes(array $routes): void;
}