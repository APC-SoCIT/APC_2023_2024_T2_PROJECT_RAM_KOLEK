<?php

namespace App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Faculty\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ProjectSubmissionStatus;
use Illuminate\Database\Eloquent\Model;

class CreateProjectSubmission extends CreateRecord
{
    protected static string $resource = ProjectSubmissionResource::class;
    protected function afterCreate(): void
    {
        ProjectSubmissionStatus::create([
            'project_submission_id' => $this->record->id,
            'user_id' => auth()->user()->id,
            'status' => 'pending',
            'type' => 'professor',
        ]);
    }

}
