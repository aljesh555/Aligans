<?php

namespace App\Filament\Resources\EventTeamResource\Pages;

use App\Filament\Resources\EventTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventTeams extends ListRecords
{
    protected static string $resource = EventTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
