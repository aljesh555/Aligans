<?php

namespace App\Filament\Resources\EventTeamResource\Pages;

use App\Filament\Resources\EventTeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventTeam extends EditRecord
{
    protected static string $resource = EventTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
