<?php

namespace App\Filament\Student\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Student\Resources\ProofreadingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\User;

class EditProofreadingRequest extends EditRecord
{
    protected static string $resource = ProofreadingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getSavedNotification(): ?Notification
    {
        $recipient = User::where('id', $this->record->endorser_id)->get();
        return Notification::make()
            ->title(auth()->user()->email.' updated a proofreading request.')
            ->body($this->record->projectSubmission->title.' proofreading request updated.')
            ->sendToDatabase($recipient);            
    }
}
