<?php declare(strict_types=1);

namespace Jahir\Framework\Routing;

use Jahir\Framework\Http\Request;
use Psr\Container\ContainerInterface;

interface RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container): array;

}