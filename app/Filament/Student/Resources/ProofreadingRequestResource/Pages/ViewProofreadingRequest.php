<?php

namespace App\Filament\Student\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Student\Resources\ProofreadingRequestResource;
use App\Models\ProofreadingRequest;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProofreadingRequest extends ViewRecord
{
    protected static string $resource = ProofreadingRequestResource::class;
    protected function getFooterWidgets(): array
    {
        return [
            ProofreadingRequestResource\Widgets\ProofreadingRequestStatusHistory::class,
            ProofreadingRequestResource\Widgets\TeamMembers::class,
        ];
    }
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if(!empty($this->record->latestStatus)){
            $data['proofreading_status'] = $this->record->latestStatus->status;
            $data['feedback'] = $this->record->latestStatus->feedback;
            $data['attachments'] = $this->record->latestStatus->attachments;
            $data['attachments_names'] = $this->record->latestStatus->attachments_names;
        }
        
        return $data;
    }
}
