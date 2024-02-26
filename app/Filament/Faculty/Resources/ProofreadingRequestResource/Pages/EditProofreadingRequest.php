<?php

namespace App\Filament\Faculty\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Faculty\Resources\ProofreadingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProofreadingRequest extends EditRecord
{
    protected static string $resource = ProofreadingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
