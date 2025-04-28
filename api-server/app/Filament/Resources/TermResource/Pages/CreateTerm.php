<?php

namespace App\Filament\Resources\TermResource\Pages;

use App\Filament\Resources\TermResource;
use App\Models\Term;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTerm extends CreateRecord
{
    protected static string $resource = TermResource::class;

    // When creating a new active term, no need to deactivate other terms now
    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create($data);
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 