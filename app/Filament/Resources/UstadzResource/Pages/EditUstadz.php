<?php

namespace App\Filament\Resources\UstadzResource\Pages;

use App\Filament\Resources\UstadzResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUstadz extends EditRecord
{
    protected static string $resource = UstadzResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
