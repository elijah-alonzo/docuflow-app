<?php

namespace App\Filament\Admin\Resources\AcademicYears;

use App\Filament\Admin\Resources\AcademicYears\Pages\CreateAcademicYear;
use App\Filament\Admin\Resources\AcademicYears\Pages\EditAcademicYear;
use App\Filament\Admin\Resources\AcademicYears\Pages\ListAcademicYears;
use App\Filament\Admin\Resources\AcademicYears\Schemas\AcademicYearsForm;
use App\Filament\Admin\Resources\AcademicYears\Tables\AcademicYearsTable;
use App\Models\AcademicYear;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AcademicYearsResource extends Resource
{
    protected static ?string $model = AcademicYear::class;

    protected static UnitEnum|string|null $navigationGroup = 'Academic Management';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Academic Year';

    protected static ?int $navigationSort = 40;

    protected static function canManageAcademicYears(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(['Admin', 'Dean', 'Staff', 'Registrar']);
    }

    public static function form(Schema $schema): Schema
    {
        return AcademicYearsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademicYearsTable::configure($table);
    }

    public static function getModelLabel(): string
    {
        return 'Academic Year';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Academic Years';
    }

    public static function canViewAny(): bool
    {
        return static::canManageAcademicYears();
    }

    public static function canCreate(): bool
    {
        return static::canManageAcademicYears();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canManageAcademicYears();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canManageAcademicYears();
    }

    public static function canAccess(): bool
    {
        return static::canManageAcademicYears();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canManageAcademicYears();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcademicYears::route('/'),
            'create' => CreateAcademicYear::route('/create'),
            'edit' => EditAcademicYear::route('/{record}/edit'),
        ];
    }
}
