<?php declare(strict_types=1);

namespace Bruha\NetteGraphQLite\DependencyInjection;

use Bruha\NetteGraphQLite\PsrCache\PsrCache;
use Bruha\NetteGraphQLite\PsrContainer\PsrContainer;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use TheCodingMachine\GraphQLite\SchemaFactory;

/**
 * Class GraphQLiteExtension
 *
 * @package Bruha\NetteGraphQLite\DependencyInjection
 */
final class GraphQLiteExtension extends CompilerExtension
{

    private const NAMESPACES = 'namespaces';

    /**
     * @return Schema
     */
    public function getConfigSchema(): Schema
    {
        return Expect::structure(
            [
                self::NAMESPACES => Expect::listOf(Expect::string())->required()->min(1.0),
            ]
        )->required()->castTo('array');
    }

    /**
     *
     */
    public function beforeCompile(): void
    {
        /** @var mixed[] $config */
        $config  = $this->getConfig();
        $builder = $this->getContainerBuilder();

        $builder
            ->addDefinition(NULL)
            ->setFactory(PsrContainer::class);

        $builder
            ->addDefinition(NULL)
            ->setFactory(PsrCache::class);

        $schemaFactory = $builder
            ->addDefinition(NULL)
            ->setFactory(SchemaFactory::class);

        foreach ($config[self::NAMESPACES] as $namespace) {
            $schemaFactory
                ->addSetup('addControllerNamespace', [$namespace])
                ->addSetup('addTypeNamespace', [$namespace]);
        }

        $builder
            ->addDefinition(NULL)
            ->setFactory(sprintf('@%s::createSchema', $schemaFactory->getName()));
    }

}