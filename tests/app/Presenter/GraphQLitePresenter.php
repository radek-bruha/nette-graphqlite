<?php declare(strict_types=1);

namespace Tests\app\Presenter;

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
 * @package Tests\app\Presenter
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