<?php

declare(strict_types=1);

namespace App\Converters;

/**
 * Класс, реализующий конвертирование согласно заданию.
 *
 * @package App\Converters
 */
class MainConverter extends Converter
{

    /**
     * @inheritDoc
     */
    public function convert(): array
    {
        foreach ($this->table as &$item) {

            $hash = $this->getHashRow($item);

            $sum = $this->buildItemSum($item);

            if (isset($this->convertedTable[$hash])) {
                //склеивание: суммирование полей с суммой.
                $this->convertedTable[$hash][3] += $sum;
            } else {
                $item[3] = $sum;
                $this->convertedTable[$hash] = $item;
            }
        }
        return $this->convertedTable;
    }

    /**
     * @param  int  $count
     * @return float
     */
    private function getPrice(int $count): float
    {
        switch (true) {
            case $count > 10000:
                $price = 0.4;
                break;
            case $count > 1000:
                $price = 0.6;
                break;
            case $count > 300:
                $price = 0.8;
                break;
            case $count > 50:
                $price = 1.1;
                break;
            case $count > 10:
                $price = 1.5;
                break;
            default:
                $price = 2.5;
        }
        return $price;
    }

    /**
     * Вычисляет сумму для строки
     *
     * @param  array  $item
     * @return float|int
     */
    private function buildItemSum(array $item)
    {
        $count = $this->getCount($item);
        $price = $this->getPrice($count);
        $sum = $price * $count;
        $sum *= $this->isSenderPayer($item) ? 0.2 : 0.5;
        return $sum;
    }

    /**
     * Возвращает хэш строки данных. Используется для склеивания.
     * Вычисляется конкатенацией следующих полей: получатель, отправитель, тип документа, тип плательщика.
     *
     * @param  array  $item
     * @return string
     */
    private function getHashRow(array $item): string
    {
        $hashParts = [
            $this->getReceiver($item),
            $this->getSender($item),
            $this->getDocType($item),
            $this->getPayerType($item)
        ];
        return implode('_', $hashParts);
    }

}
