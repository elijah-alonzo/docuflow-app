<?php

namespace App\Filament\Admin\Resources\Approvals;

use App\Features\Documents\Models\Document;
use App\Filament\Admin\Resources\Approvals\Pages\ListDocumentApprovals;
use App\Filament\Admin\Resources\Approvals\Pages\ViewDocumentApproval;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class DocumentApprovalResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';

    protected static UnitEnum|string|null $navigationGroup = 'Document Management';

    protected static ?string $navigationLabel = 'Approval Inbox';

    protected static ?int $navigationSort = 11;

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        if (!$user) {
            return parent::getEloquentQuery()->whereRaw('1=0');
        }

        if ($user->hasRole('Admin')) {
            return parent::getEloquentQuery();
        }

        $roleIds = $user->roles->pluck('id')->toArray();

        return parent::getEloquentQuery()->where(function (Builder $query) use ($roleIds, $user) {
            $query->whereHas('currentStep', function (Builder $q) use ($roleIds) {
                $q->whereIn('assigned_role_id', $roleIds);
            })
            ->orWhereHas('approvals', function (Builder $q) use ($user) {
                $q->where('approved_by', $user->id);
            });
        });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Approval Inbox')
            ->description('Review and act on documents routed to your role for approval.')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('documentType.name')
                    ->label('Document Type')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('submittedBy.full_name')
                    ->label('Initiator')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('currentStep.step_name')
                    ->label('Waiting for')
                    ->default('Completed')
                    ->badge()
                    ->color(fn ($state) => $state ? 'info' : 'success'),
                TextColumn::make('created_at')
                    ->label('Submitted Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                ]),
            ]);
    }

    public static function getModelLabel(): string
    {
        return 'Document Approval';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Document Approvals';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentApprovals::route('/'),
            'view' => ViewDocumentApproval::route('/{record}'),
        ];
    }
}
