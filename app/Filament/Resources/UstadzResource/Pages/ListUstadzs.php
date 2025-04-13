<?php

namespace App\Filament\Resources\UstadzResource\Pages;

use App\Filament\Resources\UstadzResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUstadzs extends ListRecords
{
    protected static string $resource = UstadzResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
