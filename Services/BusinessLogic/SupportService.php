<?php

namespace SendCloud\SendCloud\Services\BusinessLogic;

use SendCloud\SendCloud\Api\ConfigInterface;

class SupportService
{
    private const SUPPORT_ENDPOINT_PASSWORD_HASH = '$2y$10$Ajni0UGvMm1QXdqy86kXf.jM7GTcceTyjQlKgyG5nKJdxyGS8H7V6';
    private const DEBUG = 100;
    private const INFO = 200;
    private const NOTICE = 250;
    private const WARNING = 300;
    private const ERROR = 400;
    private const CRITICAL = 500;
    private const ALERT = 550;

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * SupportService Constructor.
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Updates system configuration parameter.
     *
     * @param array $payload
     *
     * @return array
     */
    public function update(array $payload): array
    {
        if (!$this->validateSupportPassword($payload)) {
            return ['message' => 'Sendcloud support password not valid.'];
        }

        if (array_key_exists('min_log_level', $payload)) {
            $minLogLevel = $this->isMinLogLevelCorrect((int)$payload['min_log_level']) ?
                (int)$payload['min_log_level'] : self::WARNING;
            $this->config->saveMinimalLogLevel($minLogLevel);

            return ['message' => 'Minimal log level updated.'];
        }

        return ['message' => 'Minimal log level not sent in request.'];
    }

    /**
     * Returns system configuration parameters.
     *
     * @param array $payload
     *
     * @return array
     */
    public function get(array $payload): array
    {
        if (!$this->validateSupportPassword($payload)) {
            return ['message' => 'Sendcloud support password not valid.'];
        }

        return [
            'MIN_LOG_LEVEL' => $this->config->getMinimalLogLevel()
        ];
    }

    /**
     * @param int $minLogLevel
     *
     * @return bool
     */
    private function isMinLogLevelCorrect(int $minLogLevel): bool
    {
        return
            $minLogLevel === self::DEBUG ||
            $minLogLevel === self::INFO ||
            $minLogLevel === self::NOTICE ||
            $minLogLevel === self::WARNING ||
            $minLogLevel === self::ERROR ||
            $minLogLevel === self::CRITICAL ||
            $minLogLevel === self::ALERT;
    }

    /**
     * @param array $payload
     *
     * @return bool
     */
    private function validateSupportPassword(array $payload): bool
    {
        return array_key_exists('support_password', $payload) &&
            password_verify($payload['support_password'], self::SUPPORT_ENDPOINT_PASSWORD_HASH);
    }
}
