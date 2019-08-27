<?php declare(strict_types=1);

namespace Tests\app;

use Nette\Configurator;

/**
 * Class Bootstrap
 *
 * @package Tests\app
 */
final class Bootstrap
{

    private const DIRECTORY = __DIR__ . '/../../var/log';

    /**
     * @return Configurator
     */
    public static function boot(): Configurator
    {
        $configurator = (new Configurator())
            ->setDebugMode(TRUE)
            ->setTimeZone('Europe/Prague')
            ->setTempDirectory(__DIR__ . '/../../var')
            ->addConfig(__DIR__ . '/Config/config.neon');

        if (!is_dir(self::DIRECTORY)) {
            mkdir(self::DIRECTORY, 0777, TRUE);
        }

        $configurator->enableTracy(self::DIRECTORY);

        return $configurator;
    }

}