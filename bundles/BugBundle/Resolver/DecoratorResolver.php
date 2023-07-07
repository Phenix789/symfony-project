<?php

namespace Bundle\Bug\Resolver;

use ReflectionProperty;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\TraceableValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;

class DecoratorResolver implements ArgumentResolverInterface
{
    public function __construct(
        private readonly ArgumentResolverInterface $inner,
    ) {
    }

    public function getArguments(Request $request, callable $controller): array
    {
        $this->dumpInternalResolvers();

        return $this->inner->getArguments($request, $controller);
    }

    private function dumpInternalResolvers(): void
    {
        // Helper
        $getValue = static function (object $instance, string $property): mixed {
            $property = new ReflectionProperty($instance, $property);
            $property->setAccessible(true);
            return $property->getValue($instance);
        };

        // Unwrap
        $resolvers = iterator_to_array($getValue($this->inner, 'argumentValueResolvers'));
        $dump = [];
        foreach ($resolvers as $resolver) {
            if ($resolver instanceof TraceableValueResolver) {
                $resolver = $getValue($resolver, 'inner');
            }

            $dump[] = $resolver::class;
        }

        dump($dump);
    }
}
