<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;
use App\Models\ProjectSubmission;
use App\Models\ProofreadingRequest;
use App\Models\Team;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Users', User::count())
            ->description('Total Users')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
            Card::make('Team', Team ::all()->count())
            ->description('Total Teams')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
            Card::make('Project Submission', ProjectSubmission::count())
            ->description('Total Project Submissions')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
            Card::make('Pending Project Submission', ProjectSubmission::where('status', 'pending')->count())
            ->color('success'),
            Card::make('Completed Project Submission', ProjectSubmission::where('status', 'approved')->count())
            ->color('success'),
            Card::make('Pending Proofreading Request', ProofreadingRequest::whereNot('status', 'completed')->count())
            ->color('success'),
            Card::make('Completed Proofreading Request', ProofreadingRequest::where('status', 'completed')->count())
            ->color('success'),
            
        ];
    }
}
