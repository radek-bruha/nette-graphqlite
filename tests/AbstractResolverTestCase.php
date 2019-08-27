<?php declare(strict_types=1);

namespace Tests;

use Nette\Application\Application;
use Nette\Application\IResponse;
use Nette\Application\Responses\JsonResponse;
use Nette\Http\IRequest as IHttpRequest;
use Nette\Http\IResponse as IHttpResponse;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\Strings;
use phpmock\phpunit\PHPMock;

/**
 * Class AbstractResolverTestCase
 *
 * @package Tests
 */
abstract class AbstractResolverTestCase extends AbstractContainerTestCase
{

    use PrivateTrait;
    use PHPMock;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var IHttpResponse
     */
    private $response;

    /**
     *
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->application = $this->container->getByType(Application::class);
        $this->response    = $this->container->getByType(IHttpResponse::class);
    }

    /**
     * @param array  $expected
     * @param array  $actual
     * @param string $message
     */
    protected function assertResponse(array $expected, array $actual, string $message = ''): void
    {
        self::assertEquals($expected, $actual, $message);
    }

    /**
     * @param string $query
     * @param array  $headers
     *
     * @return array
     */
    protected function sendRequest(string $query, array $headers = []): array
    {
        $function = $this->getFunctionMock('Nette\Http', 'header');
        $function->expects(self::any());

        $request = new Request(
            new UrlScript('https://project.local/graphql'),
            NULL,
            NULL,
            NULL,
            array_change_key_case($headers, CASE_LOWER),
            IHttpRequest::POST,
            NULL,
            NULL,
            static function () use ($query) {
                return Json::encode(['query' => $query]);
            }
        );

        $this->setProperty($this->application, 'httpRequest', $request);
        $service = $this->container->findByType(IHttpRequest::class)[0];
        $this->container->removeService($service);
        $this->container->addService($service, $request);

        /** @var JsonResponse $response */
        $response = NULL;

        $this->application->onResponse[] = static function (
            Application $application,
            IResponse $innerResponse
        ) use (&$response): void {
            $application;

            /** @var JsonResponse $response */
            $response = $innerResponse;
        };

        ob_start();
        $this->application->run();
        ob_end_clean();

        return [
            'http' => $this->response->getCode(),
            'body' => $response->getPayload(),
        ];
    }

    /**
     * @return string
     */
    protected function getRequestData(): string
    {
        return $this->getData();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getResponseData(array $data = []): array
    {
        $content = NULL;

        if (count($data) !== 0 && getenv('INFECTION') === FALSE) {
            $content = $this->saveData($data);
        }

        return Json::decode($content ?: $this->getData(FALSE), Json::FORCE_ARRAY);
    }

    /**
     * @param bool $isRequest
     *
     * @return string
     */
    private function getData(bool $isRequest = TRUE): string
    {
        $filename = $this->getPath($isRequest);

        if (!file_exists($filename)) {
            FileSystem::write($filename, '');
            self::fail(sprintf("File '%s' needs some content!", $filename));
        }

        $content = FileSystem::read($filename);

        if (strlen($content) === 0) {
            self::fail(sprintf("File '%s' needs some content!", $filename));
        }

        return $content;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function saveData(array $data = []): string
    {
        $content = Json::encode($data, Json::PRETTY);
        FileSystem::write($this->getPath(FALSE), $content);

        return $content;
    }

    /**
     * @param bool $isRequest
     *
     * @return string
     */
    private function getPath(bool $isRequest = TRUE): string
    {
        $pathInfo     = pathinfo(debug_backtrace()[2]['file']);
        $functionInfo = Strings::split(debug_backtrace()[3]['function'], '/(?=[A-Z])/');

        return sprintf(
            '%s/data/%s/%s.%s',
            $pathInfo['dirname'],
            Strings::substring($pathInfo['filename'], 0, -4),
            Strings::firstLower(implode('', array_slice($functionInfo, 1))),
            $isRequest ? 'graphql' : 'json'
        );
    }

}