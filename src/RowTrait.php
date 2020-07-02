<?php

declare(strict_types=1);

namespace App;

/**
 * Помощник для извлечения различной информации из строк таблицы.
 * s
 * @package App
 */
trait RowTrait
{
    /**
     * @param  array  $item
     * @return string
     */
    protected function getPayerType(array &$item): string
    {
        return $item[4];
    }

    /**
     * @param  array  $item
     * @return bool
     */
    protected function isSenderPayer(array &$item): bool
    {
        return $this->getPayerType($item) == 'S';
    }

    /**
     * @param  array  $item
     * @return int
     */
    protected function getCount(array &$item): int
    {
        return intval($item[3]);
    }

    /**
     * @param  array  $item
     * @return string
     */
    protected function getReceiver(array &$item): string
    {
        return $item[0];
    }

    /**
     * @param  array  $item
     * @return string
     */
    protected function getSender(array &$item): string
    {
        return $item[1];
    }

    /**
     * @param  array  $item
     * @return string
     */
    protected function getDocType(array &$item): string
    {
        return $item[2];
    }

}