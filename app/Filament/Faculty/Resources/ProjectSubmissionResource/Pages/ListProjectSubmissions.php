<?php

namespace App\Filament\Faculty\Resources\ProjectSubmissionResource\Pages;

use App\Filament\Faculty\Resources\ProjectSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ProjectSubmission;

class ListProjectSubmissions extends ListRecords
{
    protected static string $resource = ProjectSubmissionResource::class;

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
        ->badge(ProjectSubmission::count());
        $tabs['pending'] = Tab::make('Pending')
        ->modifyQueryUsing((fn (Builder $query) => $query->where('status','pending')))
        ->badge(ProjectSubmission::where('status','pending')->count());
        $tabs['complete'] = Tab::make('Complete')
        ->modifyQueryUsing((fn (Builder $query) => $query->where('status','approved')))
        ->badge(ProjectSubmission::where('status','approved')->count());
        $tabs['archived'] = Tab::make('Archived')
        ->modifyQueryUsing((fn (Builder $query) => $query->onlyTrashed()))
        ->badge(ProjectSubmission::onlyTrashed()->count());
        return $tabs;
    }
}
