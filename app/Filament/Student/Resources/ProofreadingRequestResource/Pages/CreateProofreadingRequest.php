<?php

namespace App\Filament\Student\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Student\Resources\ProofreadingRequestResource;
use App\Models\ProofreadingRequestStatus;
use App\Models\ProofreadingRequest;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProofreadingRequest extends CreateRecord
{
    protected static string $resource = ProofreadingRequestResource::class;
    protected function afterCreate(): void
    {
        ProofreadingRequest::where('id',$this->record->id)->update([
            'owner_id' => auth()->user()->id,
            'endorser_id' => $this->record->projectSubmission->professor_id,
        ]);
        ProofreadingRequestStatus::create([
            'proofreading_request_id' => $this->record->id,
            'user_id' => $this->record->projectSubmission->professor_id,
            'status' => 'pending',
            'type' => 'professor',
        ]);
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['endorser_id'] = $this->record->projectSubmission->professor_id;
        $data['owner_id'] = auth()->user()->id;
        dd($data);
        return $data;
    }
}
