<?php

namespace App\Filament\Faculty\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Faculty\Resources\ProofreadingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ProofreadingRequest;
use App\Models\User;
use App\Models\ProofreadingRequestStatus;
use Filament\Notifications\Notification;


class CreateProofreadingRequest extends CreateRecord
{
    protected static string $resource = ProofreadingRequestResource::class;
    protected function afterCreate(): void
    {
        ProofreadingRequest::where('id',$this->record->id)->update([
            'team_id' => $this->record->projectSubmission->team_id,
            'endorser_id' => $this->record->projectSubmission->professor_id,
        ]);
        ProofreadingRequestStatus::create([
            'proofreading_request_id' => $this->record->id,
            'user_id' => $this->record->projectSubmission->professor_id,
            'status' => 'pending',
            'type' => 'professor',
        ]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        $recipient = User::where('id', $this->record->endorser_id)->get();
        return Notification::make()
            ->title(auth()->user()->email.' created a proofreading request.')
            ->body($this->record->projectSubmission->title.' proofreading request created.')
            ->sendToDatabase($recipient);            
    }
}
