<?php

namespace App\Filament\Faculty\Resources\ProofreadingRequestResource\Pages;

use App\Filament\Faculty\Resources\ProofreadingRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ProofreadingRequest;

class ListProofreadingRequests extends ListRecords
{
    protected static string $resource = ProofreadingRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];
        $tabs['all'] = Tab::make('All')
        ->badge(ProofreadingRequest::count());
        $tabs['pending'] = Tab::make('Pending')
        ->modifyQueryUsing((fn (Builder $query) => $query->whereNot('status','completed')))
        ->badge(ProofreadingRequest::where('status','pending')->count());
        $tabs['complete'] = Tab::make('Complete')
        ->modifyQueryUsing((fn (Builder $query) => $query->where('status','completed')))
        ->badge(ProofreadingRequest::where('status','completed')->count());
        $tabs['archived'] = Tab::make('Archived')
        ->modifyQueryUsing((fn (Builder $query) => $query->onlyTrashed()))
        ->badge(ProofreadingRequest::onlyTrashed()->count());
        return $tabs;
    }
}
