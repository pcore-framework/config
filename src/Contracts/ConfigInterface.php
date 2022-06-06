<?php

declare(strict_types=1);

namespace PCore\Config\Contracts;

/**
 * Interface ConfigInterface
 * @package PCore\Config\Contracts
 * @github https://github.com/pcore-framework/config
 */
interface ConfigInterface
{

    public function get(string $key, $default = null);

}