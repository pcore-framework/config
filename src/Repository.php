<?php

declare(strict_types=1);

namespace PCore\Config;

use PCore\Config\Contracts\ConfigInterface;
use PCore\Utils\Arr;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use function pathinfo;

/**
 * Class Repository
 * @package PCore\Config
 * @github https://github.com/pcore-framework/config
 */
class Repository implements ConfigInterface
{

    /**
     * Конфигурационный массив
     *
     * @var array
     */
    protected array $items = [];

    /**
     * Получить
     *
     * @param string|null $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null): mixed
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * Настройка
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        Arr::set($this->items, $key, $value);
    }

    /**
     * Просмотреть каталог
     *
     * @param string|array $dirs
     * @return void
     */
    public function scan(string|array $dirs): void
    {
        foreach ((array)$dirs as $dir) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
            /** @var SplFileInfo $file */
            foreach ($files as $file) {
                if (!$file->isFile()) {
                    continue;
                }
                $path = $file->getRealPath() ?: $file->getPathname();
                if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') {
                    continue;
                }
                $this->loadOne($path);
                gc_mem_caches();
            }
        }
    }

    /**
     * Все
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Загрузка нескольких файлов конфигурации
     *
     * @param string|array $files
     */
    public function load(string|array $files): void
    {
        is_array($files) ? $this->loadMany($files) : $this->loadOne($files);
    }

    /**
     * @param array $files
     * @return void
     */
    public function loadMany(array $files): void
    {
        foreach ($files as $file) {
            $this->loadOne($file);
        }
    }

    /**
     * Загрузить файл конфигурации
     *
     * @param string $file
     */
    public function loadOne(string $file): void
    {
        $this->items[pathinfo($file, PATHINFO_FILENAME)] = include_once $file;
    }

}