<?php

declare(strict_types=1);

namespace App\Converters;

use App\RowTrait;

/**
 * Класс для конвертирования исходных данных.
 *
 * @package App\Converters
 */
abstract class Converter
{
    use RowTrait;

    /**
     * Массив исходных данных.
     *
     * @var array
     */
    protected array $table;

    /**
     * Результат конвертирования.
     *
     * @var array
     */
    protected array $convertedTable = [];

    /**
     * Converter constructor.
     * @param  array  $table
     */
    public function __construct(array $table)
    {
        $this->table = $table;
    }

    /**
     * Возвращает конвертированную таблицу.
     *
     * @return array
     */
    abstract public function convert(): array ;

}
