<?php

namespace App\Filament\Resources\NilaiResource\Pages;

use App\Filament\Exports\NilaiExporter;
use App\Filament\Imports\NilaiImporter;
use App\Filament\Resources\NilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNilais extends ListRecords
{
    protected static string $resource = NilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->label('Export Nilai')
                ->exporter(NilaiExporter::class)
                ->icon('heroicon-o-arrow-down-tray'),
            Actions\ImportAction::make()
                ->label('Import Nilai')
                ->importer(NilaiImporter::class)
                ->icon('heroicon-o-arrow-up-tray')
                ->extraModalFooterActions([
                    Actions\Action::make('downloadTemplate')
                        ->label('Download Template')
                        ->url(asset('templates/nilai_import_template.csv'))
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('gray')
                        ->extraAttributes(['target' => '_blank'])
                ]),
        ];
    }
}
