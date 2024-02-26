<?php

namespace App\Filament\Faculty\Resources\TeamResource\Pages;

use App\Filament\Faculty\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\User;
use App\Models\UserTeam;

class CreateTeam extends CreateRecord
{
    protected static string $resource = TeamResource::class;
    protected function getCreatedNotification(): ?Notification
    {
        $usersTeam = UserTeam::where('team_id', $this->record->id)->pluck('user_id')->toArray();
        $users =  User::whereIn('id', $usersTeam)->get();
        return Notification::make()
            ->title(auth()->user()->email.' created a team.')
            ->body('Team '.$this->record->name.' has been created.')
            ->sendToDatabase($users);            
    }
}
