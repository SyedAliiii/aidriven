<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DynamicReportExport implements FromArray, WithHeadings
{
    /**
     * @param array<int, string> $columns
     * @param array<int, array<string, mixed>> $rows
     */
    public function __construct(
        private readonly array $columns,
        private readonly array $rows,
    ) {
    }

    public function array(): array
    {
        if (empty($this->rows)) {
            return [];
        }

        // Export in the same column order as the computed headings.
        return array_map(function (array $row) {
            return array_map(function (string $column) use ($row) {
                return $row[$column] ?? null;
            }, $this->columns);
        }, $this->rows);
    }

    public function headings(): array
    {
        return $this->columns;
    }
}

