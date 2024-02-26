<?php

namespace App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Faculty\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Models\UserTeam;

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
    protected function getSavedNotification(): ?Notification
    {
        $usersTeam = UserTeam::where('team_id', $this->record->team_id)->pluck('user_id')->toArray();
        $users =  User::whereIn('id', $usersTeam)->get();
        return Notification::make()
            ->title(auth()->user()->email.' updated a project submission.')
            ->body($this->record->title.' Updated')
            ->sendToDatabase($users);
    }
}
