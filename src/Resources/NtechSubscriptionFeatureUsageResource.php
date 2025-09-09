<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource\Pages;

use BackedEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use NtechServices\SubscriptionSystem\Models\SubscriptionFeatureUsage as NtechSubscriptionFeatureUsage;
use NtechServices\SubscriptionSystem\Services\FeatureLimitationService;
use UnitEnum;

class NtechSubscriptionFeatureUsageResource extends Resource
{
    protected static ?string $model = NtechSubscriptionFeatureUsage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Feature Usage';

    protected static string|UnitEnum|null $navigationGroup = 'Ntech-Services';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('subscription_id')
                    ->label('Subscription')
                    ->relationship('subscription', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                        $record->subscribable?->name . ' (' . $record->id . ')'
                    )
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->columnSpan(1),

                Forms\Components\Select::make('feature_id')
                    ->label('Feature')
                    ->relationship('feature', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->columnSpan(1),

                Forms\Components\TextInput::make('used')
                    ->label('Used')
                    ->numeric()
                    ->disabled()
                    ->columnSpan(1),

                Forms\Components\TextInput::make('limit')
                    ->label('Limit')
                    ->numeric()
                    ->disabled()
                    ->placeholder('Unlimited')
                    ->columnSpan(1),

                Forms\Components\DateTimePicker::make('period_start')
                    ->label('Period Start')
                    ->disabled()
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\DateTimePicker::make('period_end')
                    ->label('Period End')
                    ->disabled()
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\Placeholder::make('usage_percentage')
                    ->label('Usage Percentage')
                    ->content(function ($record) {
                        if (!$record || !$record->limit) {
                            return 'N/A (Unlimited)';
                        }
                        $percentage = round(($record->used / $record->limit) * 100, 2);
                        return $percentage . '%';
                    })
                    ->columnSpan(1),

                Forms\Components\Placeholder::make('status')
                    ->label('Status')
                    ->content(function ($record) {
                        if (!$record) return 'N/A';
                        
                        if (!$record->limit) {
                            return 'Unlimited';
                        }
                        
                        $percentage = ($record->used / $record->limit) * 100;
                        
                        if ($percentage >= 100) {
                            return 'Over Limit';
                        } elseif ($percentage >= 80) {
                            return 'Near Limit';
                        } else {
                            return 'Within Limit';
                        }
                    })
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('subscription.subscribable.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->placeholder('No project'),

                TextColumn::make('feature.name')
                    ->label('Feature')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('used')
                    ->label('Used')
                    ->numeric()
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->formatStateUsing(fn ($state) => number_format($state)),

                TextColumn::make('limit')
                    ->label('Limit')
                    ->numeric()
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->formatStateUsing(fn ($state) => $state ? number_format($state) : 'Unlimited')
                    ->placeholder('Unlimited'),

                TextColumn::make('usage_percentage')
                    ->label('Usage %')
                    ->getStateUsing(function ($record) {
                        if (!$record->limit) return null;
                        return round(($record->used / $record->limit) * 100, 1);
                    })
                    ->formatStateUsing(fn ($state) => $state !== null ? $state . '%' : 'N/A')
                    ->color(fn ($state) => match(true) {
                        $state === null => 'gray',
                        $state >= 100 => 'danger',
                        $state >= 80 => 'warning',
                        $state >= 50 => 'info',
                        default => 'success',
                    })
                    ->weight('bold')
                    ->alignment(Alignment::Center),

                IconColumn::make('status_icon')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if (!$record->limit) return 'unlimited';
                        
                        $percentage = ($record->used / $record->limit) * 100;
                        
                        if ($percentage >= 100) return 'over_limit';
                        if ($percentage >= 80) return 'near_limit';
                        return 'within_limit';
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'over_limit' => 'heroicon-o-exclamation-triangle',
                        'near_limit' => 'heroicon-o-exclamation-circle',
                        'within_limit' => 'heroicon-o-check-circle',
                        'unlimited' => 'heroicon-o-infinity',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'over_limit' => 'danger',
                        'near_limit' => 'warning',
                        'within_limit' => 'success',
                        'unlimited' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('period_start')
                    ->label('Period Start')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('period_end')
                    ->label('Period End')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('feature_id')
                    ->label('Feature')
                    ->relationship('feature', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('subscription.subscribable')
                    ->label('Project')
                    ->options(function () {
                        return static::getModel()::with('subscription.subscribable')
                            ->get()
                            ->pluck('subscription.subscribable.name', 'subscription.subscribable.id')
                            ->filter()
                            ->unique()
                            ->toArray();
                    })
                    ->searchable(),

                Filter::make('over_limit')
                    ->label('Over Limit')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereColumn('used', '>', 'limit')
                              ->whereNotNull('limit')
                    )
                    ->toggle(),

                Filter::make('near_limit')
                    ->label('Near Limit (80%+)')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereRaw('(used / limit) >= 0.8')
                              ->whereNotNull('limit')
                              ->whereColumn('used', '<=', 'limit')
                    )
                    ->toggle(),

                Filter::make('unlimited')
                    ->label('Unlimited Features')
                    ->query(fn (Builder $query): Builder => $query->whereNull('limit'))
                    ->toggle(),

                Filter::make('current_period')
                    ->label('Current Period')
                    ->query(fn (Builder $query): Builder =>
                        $query->where('period_start', '<=', now())
                              ->where('period_end', '>=', now())
                    )
                    ->toggle(),

                Filter::make('period_dates')
                    ->schema([
                        DatePicker::make('period_from')
                            ->label('Period From')
                            ->native(false),
                        DatePicker::make('period_until')
                            ->label('Period Until')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['period_from'], fn ($query, $date) =>
                                $query->whereDate('period_start', '>=', $date)
                            )
                            ->when($data['period_until'], fn ($query, $date) =>
                                $query->whereDate('period_end', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn () => false), // Usually readonly data

                    Action::make('reset_usage')
                        ->label('Reset Usage')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function ($record) {
                            $record->update(['used' => 0]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Usage reset')
                                ->success()
                                ->body("Usage for feature '{$record->feature->name}' has been reset to 0.")
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to reset the usage counter to 0?'),

                    Action::make('extend_limit')
                        ->label('Extend Limit')
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('additional_limit')
                                ->label('Additional Limit')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->helperText('Amount to add to current limit'),
                        ])
                        ->action(function ($record, array $data) {
                            if ($record->limit) {
                                $newLimit = $record->limit + $data['additional_limit'];
                                $record->update(['limit' => $newLimit]);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Limit extended')
                                ->success()
                                ->body("Limit increased by {$data['additional_limit']} units.")
                                ->send();
                        })
                        ->visible(fn ($record) => $record->limit !== null),

                    DeleteAction::make()
                        ->visible(fn () => false), // Usually don't delete usage records
                ])
                ->label('Actions')
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
            ])
            ->headerActions([
                // Usually no create action for usage records as they're system generated
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    BulkAction::make('reset_usage')
                        ->label('Reset Usage')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(fn ($records) =>
                            $records->each(fn ($record) =>
                                $record->update(['used' => 0])
                            )
                        )
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    // Usually don't allow bulk delete of usage records
                ]),
            ])
            ->emptyStateActions([
                // No create action typically needed
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['subscription.subscribable', 'feature']))
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Navigation Badge
    public static function getNavigationBadge(): ?string
    {
        $overLimitCount = static::getModel()::whereColumn('used', '>', 'limit')
            ->whereNotNull('limit')
            ->count();
        
        return $overLimitCount > 0 ? (string) $overLimitCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overLimitCount = static::getModel()::whereColumn('used', '>', 'limit')
            ->whereNotNull('limit')
            ->count();
        
        return $overLimitCount > 0 ? 'danger' : null;
    }

    // Global Search
    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->feature?->name . ' - ' . ($record->subscription?->subscribable?->name ?? 'No Project');
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $percentage = $record->limit ? round(($record->used / $record->limit) * 100, 1) : null;
        
        return [
            'Project' => $record->subscription?->subscribable?->name ?? 'No Project',
            'Used' => number_format($record->used) . ($record->limit ? '/' . number_format($record->limit) : ''),
            'Usage' => $percentage ? $percentage . '%' : 'Unlimited',
            'Period' => $record->period_start ? $record->period_start->format('M Y') : 'N/A',
        ];
    }

    // Disable create/edit pages since this is usually system-generated data
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNtechSubscriptionFeatureUsages::route('/'),
            // 'create' => Pages\CreateNtechSubscriptionFeatureUsage::route('/create'),
            // 'edit' => Pages\EditNtechSubscriptionFeatureUsage::route('/{record}/edit'),
        ];
    }

    // Helper method to check if user can manage usage (optional)
    public static function canCreate(): bool
    {
        return false; // Usage records are typically system-generated
    }

   

    public static function canDelete(Model $record): bool
    {
        return false; // Usually preserve usage history
    }
}