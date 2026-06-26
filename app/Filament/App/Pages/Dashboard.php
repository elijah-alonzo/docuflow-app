<?php

namespace App\Filament\App\Pages;

use App\Features\DocumentSubmissions\Models\DocumentSubmission;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = ' ';

    protected static bool $shouldRegisterNavigation = false;

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 2,
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                View::make('app.home.page')
                    ->viewData(fn (): array => [
                        'user' => auth()->user(),
                        'submissions' => $this->getSubmissions(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function getSubmissions(): Collection
    {
        $userId = auth()->id();

        return DocumentSubmission::query()
            ->where(function ($query) use ($userId) {
                $query->where('created_by', $userId)
                    ->orWhereHas('uploaders', fn ($uploaderQuery) => $uploaderQuery->where('user_id', $userId));
            })
            ->with([
                'documentCategory.fields',
                'currentStep',
            ])
            ->orderByDesc('updated_at')
            ->get();
    }
}