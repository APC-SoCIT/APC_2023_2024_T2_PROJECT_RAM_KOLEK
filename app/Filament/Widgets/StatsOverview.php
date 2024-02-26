<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;
use App\Models\ProjectSubmission;
use App\Models\Team;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Users', User::count()),
            Card::make('Project Submissions', ProjectSubmission::count()),
            Card::make('Teams', Team::count())
        ];
    }
}
