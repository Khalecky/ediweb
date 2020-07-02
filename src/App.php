<?php

declare(strict_types=1);

namespace App;

use App\Converters\MainConverter;

class App
{
    /**
     * @var array
     */
    private array $data = [];

    public function __construct(string $filePath)
    {
        $this->readData($filePath);
    }

    /**
     * @param  string  $filePath
     * @return App
     */
    public static function create(string $filePath): App
    {
        return new self($filePath);
    }

    /**
     * @return Report|null
     */
    public function getReport(): ?Report
    {
        if (!$this->data)
            return null;

        $converter = new MainConverter($this->data);
        $builder = new ReportBuilder($converter);

        return $builder->build();
    }

    /**
     * @param  string  $filePath
     */
    private function readData(string $filePath)
    {
        if (!file_exists($filePath))
            return;

        $this->data = array_map('str_getcsv', file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        array_shift($this->data);
    }

}
