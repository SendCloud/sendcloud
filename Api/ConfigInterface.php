<?php

namespace SendCloud\SendCloud\Api;

/**
 * Interface ConfigInterface
 *
 * @package SendCloud\SendCloud\Api
 */
interface ConfigInterface
{
    /**
     * Retrieves minimal log level.
     *
     * @return string|null
     */
    public function getMinimalLogLevel(): ?string;

    /**
     * Sets minimal log level.
     *
     * @param int $logLevel
     *
     * @return void
     */
    public function saveMinimalLogLevel(int $logLevel): void;
}
