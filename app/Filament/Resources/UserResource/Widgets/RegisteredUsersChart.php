<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use app\Models\User;

class RegisteredUsersChart extends ChartWidget
{
    protected static ?string $heading = 'User Registration Chart';

    protected function getData(): array
    {
        $data = Trend::model(model:User::class)
        ->between(
            now()->subMonths(5),
            now()
        )
        ->perMonth()
        ->count();
        return [
            'datasets' => [
                [
                    'label' => 'User registrations this semester',
                    'data' =>  $data->map(fn($value) => $value->aggregate)
                ],
            ],
            'labels' => $data->map(fn($value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
