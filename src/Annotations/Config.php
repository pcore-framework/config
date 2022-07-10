<?php

declare(strict_types=1);

namespace PCore\Config\Annotations;

use Attribute;
use PCore\Aop\Contracts\PropertyAttribute;
use PCore\Aop\Exceptions\PropertyHandleException;
use PCore\Config\Contracts\ConfigInterface;
use PCore\Di\{Context, Reflection};

/**
 * Class Config
 * @package PCore\Config\Annotations
 * @github https://github.com/pcore-framework/config
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Config implements PropertyAttribute
{

    /**
     * @param string $key ключ
     * @param mixed|null $default значение по умолчанию
     */
    public function __construct(
        protected string $key,
        protected mixed $default = null
    )
    {
    }

    public function handle(object $object, string $property): void
    {
        try {
            $container = Context::getContainer();
            $reflectionProperty = Reflection::property($object::class, $property);
            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($object, $container->make(ConfigInterface::class)->get($this->key, $this->default));
        } catch (\Throwable $throwable) {
            throw new PropertyHandleException('Не удалось назначить свойство.' . $throwable->getMessage());
        }
    }

}