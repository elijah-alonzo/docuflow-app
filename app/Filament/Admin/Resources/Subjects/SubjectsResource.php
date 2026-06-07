<?php

namespace App\Filament\Admin\Resources\Subjects;

use App\Filament\Admin\Resources\Subjects\Pages\CreateSubjects;
use App\Filament\Admin\Resources\Subjects\Pages\EditSubjects;
use App\Filament\Admin\Resources\Subjects\Pages\ListSubjects;
use App\Filament\Admin\Resources\Subjects\Schemas\SubjectsForm;
use App\Filament\Admin\Resources\Subjects\Tables\SubjectsTable;
use App\Models\Subject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SubjectsResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Subjects';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return SubjectsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SubjectsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user?->hasRole('Registrar') && $user->program_id) {
            return $query->where('program_id', $user->program_id);
        }

        return $query;
    }

    public static function getModelLabel(): string
    {
        return 'Subject';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Subjects';
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubjects::route('/'),
            'create' => CreateSubjects::route('/create'),
            'edit' => EditSubjects::route('/{record}/edit'),
        ];
    }
}
