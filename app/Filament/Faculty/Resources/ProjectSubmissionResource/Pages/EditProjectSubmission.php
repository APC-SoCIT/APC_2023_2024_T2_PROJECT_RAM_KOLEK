<?php

namespace App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Faculty\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectSubmission extends EditRecord
{
    protected static string $resource = ProjectSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
