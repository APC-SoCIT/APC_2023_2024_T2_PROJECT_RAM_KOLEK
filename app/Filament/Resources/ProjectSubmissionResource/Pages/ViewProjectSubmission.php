<?php

namespace App\Filament\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use App\Models\ProjectSubmissionStatus;
use App\Models\ProjectSubmission;
use App\Models\User;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Set;
use Filament\Filament;

class ViewProjectSubmission extends ViewRecord
{

    protected static string $resource = ProjectSubmissionResource::class;

    protected function getHeaderActions(): array
    {

        return [
            Action::make('approve')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (ProjectSubmission $project) {
                $user = auth()->user();
                return ProjectSubmissionStatus::create([
                    'project_submission_id' => $project->id,
                    'user_id' => $user->id,
                    'status' => 'approved',
                    'type' => 'professor',
                ]);
            }),
            Action::make('return')
            ->requiresConfirmation()
            ->action(function (ProjectSubmission $project) {
                $user = auth()->user();
                return ProjectSubmissionStatus::create([
                    'project_submission_id' => $project->id,
                    'user_id' => $user->id,
                    'status' => 'returned',
                    'type' => 'professor',
                ]);
            }),
            ];
    }

}
