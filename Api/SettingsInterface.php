<?php
namespace CreativeICT\SendCloud\Api;

interface SettingsInterface
{
    /**
     * @return mixed
     */
    public function getModuleInformation();

    /**
     * @api
     * @param string $script_url
     * @return string
     */
    public function setScriptUrl($script_url);
}