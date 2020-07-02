<?php

declare(strict_types=1);

namespace App;

class Report
{

    /**
     * Массив для хранения имен компаний с индексом, равным ID компании.
     *
     * @var array
     */
    private array $companies = [];

    private float $totalReceiver = 0.0;
    private float $totalSender = 0.0;

    /**
     * Таблица типов документа с процентом содержанием в документе.
     * @var array
     */
    private array $docTypesInfo;

    public function __construct()
    {
    }

    /**
     * Возвращает итоговую сумму по получателю.
     *
     * @return string
     */
    public function getTotalReceiver(): string
    {
        return sprintf('%.2f', $this->totalReceiver);
    }

    /**
     * @param  float  $totalReceiver
     * @return Report
     */
    public function setTotalReceiver(float $totalReceiver): Report
    {
        $this->totalReceiver = $totalReceiver;
        return $this;
    }

    /**
     * Возвращает итоговую сумму по отправителю.
     *
     * @return string
     */
    public function getTotalSender(): string
    {
        return sprintf('%.02f', $this->totalSender);
    }

    /**
     * @param  float  $totalSender
     * @return Report
     */
    public function setTotalSender(float $totalSender): Report
    {
        $this->totalSender = $totalSender;
        return $this;
    }

    /**
     * @param  array  $companies
     * @return $this
     */
    public function setCompanies(array $companies)
    {
        $this->companies = $companies;
        return $this;
    }


    /**
     * Возвращает относительную разность между Получателем и Отправителем в процентах.
     *
     * @return string
     */
    public function getRelativeDiff(): string
    {
        try {
            $diff = 100.0 * ($this->totalSender - $this->totalReceiver) / $this->totalReceiver;
        } catch (\Exception $e) {
            $diff = 0.0;
        }

        return sprintf('%.1f%%', $diff);
    }

    /**
     * Возвращает количество типов документов.
     *
     * @param  bool  $percentMode   Режим отображения в процентном отношении от общего числа
     * @return array
     */
    public function getDocTypeInfo(bool $percentMode = true): array
    {
        if (!$percentMode) {
            return $this->docTypesInfo;
        }

        $docTypesInfo = $this->docTypesInfo;
        $count = array_sum($docTypesInfo);
        array_walk($docTypesInfo, function (&$item) use ($count) {
            $item = sprintf('%.1f%%', 100.0 * $item / $count);
        });
        return $docTypesInfo;
    }

    /**
     * @param  array  $docTypesInfo
     * @return $this
     */
    public function setDocTypesInfo(array $docTypesInfo): Report
    {
        $this->docTypesInfo = $docTypesInfo;
        return $this;
    }

    /**
     * Возвращает итоговую сумму.
     *
     * @return string
     */
    public function getTotal(): string
    {
        return sprintf('%.2f', $this->totalReceiver + $this->totalSender);
    }

    /**
     * @param  string  $companyID
     */
    public function appendCompany(string $companyID)
    {
        if (isset($this->companies[$companyID])) {
            return;
        }

        $this->companies[$companyID] = 'company_'.(count($this->companies) + 1);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $rows = [
            "Итого: ".$this->getTotal(),
            "Итого получатель: ".$this->getTotalReceiver(),
            "Итого отправитель: ".$this->getTotalSender(),
            "Относительная разность: ".$this->getRelativeDiff()
        ];
        $rows[] = str_repeat('-', 20);

        $rows[] = "Статистика по типам документов:";
        $rows[] = str_repeat('-', 20);
        array_walk($this->docTypesInfo, function ($item, $key) use (&$rows) {
            $rows[] = "$key: $item";
        });

        $rows[] = str_repeat('-', 20);
        $rows[] = "Список обработанных компаний:";
        $rows[] = str_repeat('-', 20);
        array_walk($this->companies, function ($company, $id) use (&$rows) {
            $rows[] = "$company ($id)";
        });

        return implode(PHP_EOL, $rows);
    }

}
