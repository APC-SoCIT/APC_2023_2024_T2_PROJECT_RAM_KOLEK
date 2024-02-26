<?php

namespace App\Filament\Student\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Student\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\User;

class EditProjectSubmission extends EditRecord
{
    protected static string $resource = ProjectSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getSavedNotification(): ?Notification
    {
        $recipient = User::where('id', $this->record->professor_id)->get();
        return Notification::make()
            ->title(auth()->user()->email.' updated a project submission.')
            ->body($this->record->title.' was updated.')
            ->sendToDatabase($recipient);            
    }
}
