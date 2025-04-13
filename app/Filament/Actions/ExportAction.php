<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;

class ExportAction extends Action
{
    public static function make(string $name = 'export'): static
    {
        return parent::make($name);
    }

    public function exporter(string $exporterClass): static
    {
        $this->configure(function () use ($exporterClass) {
            $this->exporterClass = $exporterClass;
        });

        return $this;
    }

    public function formats(array $formats): static
    {
        $this->configure(function () use ($formats) {
            $this->formats = $formats;
        });

        return $this;
    }

    public function execute(): void
    {
        // Logic for exporting data using the exporter class and formats
    }
}