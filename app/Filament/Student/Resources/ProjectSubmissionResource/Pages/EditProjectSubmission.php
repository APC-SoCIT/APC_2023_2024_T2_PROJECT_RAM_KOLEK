<?php

namespace App\Filament\Student\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Student\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProjectSubmission extends EditRecord
{
    protected static string $resource = ProjectSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
