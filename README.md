# [**Nette Framework**](https://github.com/nette/nette) [**GraphQLite**](https://github.com/thecodingmachine/graphqlite) Extension
[![Downloads](https://img.shields.io/packagist/dt/radek-bruha/nette-graphqlite.svg?style=flat-square)](https://packagist.org/packages/radek-bruha/nette-graphqlite)
[![Build Status](https://img.shields.io/travis/radek-bruha/nette-graphqlite.svg?style=flat-square)](https://travis-ci.org/radek-bruha/nette-graphqlite)
[![Coverage Status](https://img.shields.io/coveralls/github/radek-bruha/coding-standard.svg?style=flat-square)](https://coveralls.io/github/radek-bruha/nette-graphqlite)
[![Latest Stable Version](https://img.shields.io/github/release/radek-bruha/nette-graphqlite.svg?style=flat-square)](https://github.com/radek-bruha/nette-graphqlite/releases)

**Usage**
```
composer require radek-bruha/nette-graphqlite
```

**Nette Framework Usage**

***Config.neon***
```yml
extensions:
    graphQLite: Bruha\NetteGraphQLite\DependencyInjection\GraphQLiteExtension

graphQLite:
    namespaces:
        - App
```

***GraphQLitePresenter.php***
```php
<?php declare(strict_types=1);

namespace App\Presenters;

use GraphQL\Error\Debug;
use GraphQL\Error\Error;
use GraphQL\GraphQL;
use Nette\Application\UI\Presenter;
use Nette\Http\IResponse;
use Nette\Utils\Json;
use TheCodingMachine\GraphQLite\Schema;
use Tracy\Debugger;

/**
 * Class GraphQLitePresenter
 *
 * @package App\Presenters
 */
final class GraphQLitePresenter extends Presenter
{

    /**
     * @var Schema
     */
    private Schema $schema;

    /**
     * GraphQLitePresenter constructor
     *
     * @param Schema $schema
     */
    public function __construct(Schema $schema)
    {
        parent::__construct();

        $this->schema = $schema;
    }

    /**
     *
     */
    public function actionProcess(): void
    {
        $body = Json::decode(
            is_string($this->getHttpRequest()->getRawBody()) ? $this->getHttpRequest()->getRawBody() : '{}',
            Json::FORCE_ARRAY
        );

        $this->sendJson(
            GraphQL::executeQuery(
                $this->schema,
                $body['query'] ?? '',
                NULL,
                NULL,
                $body['variables'] ?? []
            )->setErrorsHandler(
                static function (array $errors, callable $formatter): array {
                    if (Debugger::$productionMode) {
                        /** @var Error $error */
                        foreach ($errors as $error) {
                            Debugger::log(
                                is_object($error->getPrevious()) ? $error->getPrevious() : $error,
                                'GraphQLite'
                            );
                        }
                    }

                    return array_map($formatter, $errors);
                }
            )->setErrorFormatter(
                static function (Error $error): array {
                    $exception = $error->getPrevious();

                    if (is_object($exception)) {
                        return [
                            'code'    => $exception->getCode(),
                            'message' => $exception->getMessage(),
                        ];
                    }

                    return [
                        'code'    => IResponse::S400_BAD_REQUEST,
                        'message' => $error->getMessage(),
                    ];
                }
            )->toArray(Debugger::$productionMode ? FALSE : Debug::RETHROW_INTERNAL_EXCEPTIONS)
        );
    }

}
```

***GraphQLite documentation***
- [Queries](https://graphqlite.thecodingmachine.io/docs/queries)
- [Mutations](https://graphqlite.thecodingmachine.io/docs/mutations)
- [Input types](https://graphqlite.thecodingmachine.io/docs/input-types)
- [Output types](https://graphqlite.thecodingmachine.io/docs/type_mapping)
