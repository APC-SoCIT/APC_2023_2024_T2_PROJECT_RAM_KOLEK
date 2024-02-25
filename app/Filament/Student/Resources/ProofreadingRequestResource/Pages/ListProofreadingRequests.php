<?php

namespace App\Filament\Student\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Student\Resources\ProofreadingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProofreadingRequests extends ListRecords
{
    protected static string $resource = ProofreadingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
