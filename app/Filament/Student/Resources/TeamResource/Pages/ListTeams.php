<?php

namespace App\Filament\Student\Resources\TeamResource\Pages;

use App\Filament\Student\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Team;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

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
        ->badge(Team::count());
        $tabs['archived'] = Tab::make('Archived')
        ->modifyQueryUsing((fn (Builder $query) => $query->onlyTrashed()))
        ->badge(Team::onlyTrashed()->count());
        return $tabs;
    }
}
