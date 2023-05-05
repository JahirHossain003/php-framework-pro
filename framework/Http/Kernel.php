<?php declare(strict_types=1);

namespace Jahir\Framework\Http;

class Kernel
{
    public function handle(Request $request): Response
    {
        $content = "<h2>Hello from Kernel</h2>";
        return new Response($content);
    }
}