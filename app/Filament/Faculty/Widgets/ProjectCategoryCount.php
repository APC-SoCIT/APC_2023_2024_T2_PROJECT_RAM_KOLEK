<?php

// app/Filament/Faculty/Widgets/ProjectCategoryCount.php

namespace App\Filament\Faculty\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\ProjectCategories;

class ProjectCategoryCount extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Project Category Count';

    protected function getData(): array
    {
        $projectCounts = ProjectCategories::join('categories', 'project_categories.categories_id', '=', 'categories.id')
            ->select('categories.name')
            ->selectRaw('count(*) as count')
            ->groupBy('categories.name')
            ->pluck('count', 'name')
            ->toArray();

        return [
            'labels' => array_keys($projectCounts),
            'datasets' => [
                [
                    'label' => 'Project Count',
                    'data' => array_values($projectCounts),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)',
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'display' => false,
                ],
                'x' => [
                    'display' => false,
                ],
            ],
        ];
    }
    
}
