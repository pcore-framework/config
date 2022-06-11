<?php

declare(strict_types=1);

namespace PCore\Config;

/**
 * Class ConfigProvider
 * @package PCore\Config
 * @github https://github.com/pcore-framework/config
 */
class ConfigProvider
{

    public function __invoke(): array
    {
        return [
            'bindings' => [
                'PCore\Config\Contracts\ConfigInterface' => 'PCore\Config\Repository'
            ]
        ];
    }

}