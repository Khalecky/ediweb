<?php

declare(strict_types=1);

namespace App;

use App\Converters\Converter;

class ReportBuilder
{
    use RowTrait;

    /**
     * @var Report
     */
    private Report $report;

    /**
     * Массив, в котором аккумулируются различные показатели
     * @var array
     */
    private array $accumulatedData = [];

    /**
     * Массив сконвертированных данных, по которым формируется отчет.
     *
     * @var array
     */
    private array $table;

    /**
     * ReportBuilder constructor.
     * @param  Converter  $converter
     */
    public function __construct(Converter $converter)
    {
        $this->table = $converter->convert();
    }

    /**
     * @return Report|null
     */
    public function build(): ?Report
    {
        $this->report = new Report();

        $this->setReportTotals()->setReportDocTypesInfo()->setReportCompanies();

        return $this->report;
    }

    /**
     * @return ReportBuilder
     */
    private function setReportCompanies(): ReportBuilder
    {
        $report = $this->report;
        array_walk($this->table, function ($item) use ($report) {
            for ($i = 0; $i < 2; $i++) {
                $report->appendCompany($item[$i]);
            }
        });
        return $this;
    }

    /**
     * @return ReportBuilder
     */
    private function setReportTotals(): ReportBuilder
    {
        $totalSender = 0.0;
        $totalReceiver = 0.0;
        array_walk($this->table, function ($item) use (&$totalSender, &$totalReceiver) {

            if (end($item) == 'S') {
                $totalSender += $item[3];
            } else {
                $totalReceiver += $item[3];
            }
        });

        $this->report->setTotalSender($totalSender);
        $this->report->setTotalReceiver($totalReceiver);

        return $this;
    }

    private function setReportDocTypesInfo()
    {
        $columnDocType = 2;
        $docTypes = array_column($this->table, $columnDocType);

        $this->report->setDocTypesInfo(array_count_values($docTypes));
        return $this;
    }



}
