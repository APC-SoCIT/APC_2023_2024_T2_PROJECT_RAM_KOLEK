<?php

namespace App\Filament\Student\Pages;
use Filament\Support\Enums\MaxWidth;
use Filament\Pages\Page;

class PrivacyPolicy extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 5;
    protected static string $view = 'filament.student.pages.privacy-policy';

}
