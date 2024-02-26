<?php

namespace App\Filament\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Resources\ProofreadingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProofreadingRequest extends EditRecord
{
    protected static string $resource = ProofreadingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
