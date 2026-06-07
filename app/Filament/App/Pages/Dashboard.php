<?php

namespace App\Filament\App\Pages;

use App\Models\AcademicYear;
use App\Models\Load;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

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
                $this->getFiltersFormContentComponent()
                    ->columnSpanFull(),
                View::make('app.home.page')
                    ->viewData(fn (): array => [
                        'user' => auth()->user(),
                        'loads' => Load::query()
                            ->where('user_id', auth()->id())
                            ->when(! empty($this->filters['academic_year_id']), fn ($query) => $query->where('academic_year_id', $this->filters['academic_year_id']))
                            ->when(! empty($this->filters['term']), fn ($query) => $query->where('term', $this->filters['term']))
                            ->with(['program', 'subject', 'academicYear'])
                            ->orderByDesc('academic_year_id')
                            ->orderBy('term')
                            ->get(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
