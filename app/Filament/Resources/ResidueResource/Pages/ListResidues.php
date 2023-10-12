<?php

namespace App\Filament\Resources\ResidueResource\Pages;

use App\Filament\Resources\ResidueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResidues extends ListRecords
{
    protected static string $resource = ResidueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
