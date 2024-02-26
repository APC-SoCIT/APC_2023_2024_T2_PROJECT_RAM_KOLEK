<?php

namespace App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Faculty\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ProjectSubmissionStatus;
use App\Models\User;
use App\Models\UserTeam;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

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
    protected function getCreatedNotification(): ?Notification
    {
        $usersTeam = UserTeam::where('team_id', $this->record->team_id)->pluck('user_id')->toArray();
        $users =  User::whereIn('id', $usersTeam)->get();
        return Notification::make()
            ->title(auth()->user()->email.' created a project submission.')
            ->body($this->record->title.' has been created.')
            ->sendToDatabase($users);            
    }

}
