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

    protected static ?string $navigationLabel = null;

    protected static string|UnitEnum|null $navigationGroup = 'Ntech-Services';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationLabel(): string
    {
        return __('subscription-system-admin::usage.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('subscription_id')
                    ->label(__('subscription-system-admin::usage.fields.subscription'))
                    ->relationship('subscription', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                        $record->subscribable?->name . ' (' . $record->id . ')'
                    )
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->columnSpan(1),

                Forms\Components\Select::make('feature_id')
                    ->label(__('subscription-system-admin::usage.fields.feature'))
                    ->relationship('feature', 'name')
                    ->searchable()
                    ->preload()
                    ->disabled()
                    ->columnSpan(1),

                Forms\Components\TextInput::make('used')
                    ->label(__('subscription-system-admin::usage.fields.used'))
                    ->numeric()
                    ->disabled()
                    ->columnSpan(1),

                Forms\Components\TextInput::make('limit')
                    ->label(__('subscription-system-admin::usage.fields.limit'))
                    ->numeric()
                    ->disabled()
                    ->placeholder(__('subscription-system-admin::usage.unlimited'))
                    ->columnSpan(1),

                Forms\Components\DateTimePicker::make('period_start')
                    ->label(__('subscription-system-admin::usage.fields.period_start'))
                    ->disabled()
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\DateTimePicker::make('period_end')
                    ->label(__('subscription-system-admin::usage.fields.period_end'))
                    ->disabled()
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\Placeholder::make('usage_percentage')
                    ->label(__('subscription-system-admin::usage.fields.usage_percentage'))
                    ->content(function ($record) {
                        if (!$record || !$record->limit) {
                            return __('subscription-system-admin::usage.status.unlimited_na');
                        }
                        $percentage = round(($record->used / $record->limit) * 100, 2);
                        return $percentage . '%';
                    })
                    ->columnSpan(1),

                Forms\Components\Placeholder::make('status')
                    ->label(__('subscription-system-admin::usage.fields.status'))
                    ->content(function ($record) {
                        if (!$record) return __('subscription-system-admin::usage.status.na');
                        
                        if (!$record->limit) {
                            return __('subscription-system-admin::usage.status.unlimited');
                        }
                        
                        $percentage = ($record->used / $record->limit) * 100;
                        
                        if ($percentage >= 100) {
                            return __('subscription-system-admin::usage.status.over_limit');
                        } elseif ($percentage >= 80) {
                            return __('subscription-system-admin::usage.status.near_limit');
                        } else {
                            return __('subscription-system-admin::usage.status.within_limit');
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
                    ->label(__('subscription-system-admin::general.fields.id'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('subscription.subscribable.name')
                    ->label(__('subscription-system-admin::usage.fields.project'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->placeholder(__('subscription-system-admin::usage.table.no_project')),

                TextColumn::make('feature.name')
                    ->label(__('subscription-system-admin::usage.fields.feature'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('used')
                    ->label(__('subscription-system-admin::usage.fields.used'))
                    ->numeric()
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->formatStateUsing(fn ($state) => number_format($state)),

                TextColumn::make('limit')
                    ->label(__('subscription-system-admin::usage.fields.limit'))
                    ->numeric()
                    ->sortable()
                    ->alignment(Alignment::Center)
                    ->formatStateUsing(fn ($state) => $state ? number_format($state) : __('subscription-system-admin::usage.unlimited'))
                    ->placeholder(__('subscription-system-admin::usage.unlimited')),

                TextColumn::make('usage_percentage')
                    ->label(__('subscription-system-admin::usage.table.usage_percent'))
                    ->getStateUsing(function ($record) {
                        if (!$record->limit) return null;
                        return round(($record->used / $record->limit) * 100, 1);
                    })
                    ->formatStateUsing(fn ($state) => $state !== null ? $state . '%' : __('subscription-system-admin::usage.table.na'))
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
                    ->label(__('subscription-system-admin::usage.fields.status'))
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
                    ->label(__('subscription-system-admin::usage.fields.period_start'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('period_end')
                    ->label(__('subscription-system-admin::usage.fields.period_end'))
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('subscription-system-admin::general.fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('subscription-system-admin::general.fields.updated_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('feature_id')
                    ->label(__('subscription-system-admin::usage.filters.feature'))
                    ->relationship('feature', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('subscription.subscribable')
                    ->label(__('subscription-system-admin::usage.filters.project'))
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
                    ->label(__('subscription-system-admin::usage.filters.over_limit'))
                    ->query(fn (Builder $query): Builder =>
                        $query->whereColumn('used', '>', 'limit')
                              ->whereNotNull('limit')
                    )
                    ->toggle(),

                Filter::make('near_limit')
                    ->label(__('subscription-system-admin::usage.filters.near_limit'))
                    ->query(fn (Builder $query): Builder =>
                        $query->whereRaw('(used / limit) >= 0.8')
                              ->whereNotNull('limit')
                              ->whereColumn('used', '<=', 'limit')
                    )
                    ->toggle(),

                Filter::make('unlimited')
                    ->label(__('subscription-system-admin::usage.filters.unlimited'))
                    ->query(fn (Builder $query): Builder => $query->whereNull('limit'))
                    ->toggle(),

                Filter::make('current_period')
                    ->label(__('subscription-system-admin::usage.filters.current_period'))
                    ->query(fn (Builder $query): Builder =>
                        $query->where('period_start', '<=', now())
                              ->where('period_end', '>=', now())
                    )
                    ->toggle(),

                Filter::make('period_dates')
                    ->schema([
                        DatePicker::make('period_from')
                            ->label(__('subscription-system-admin::usage.filters.period_from'))
                            ->native(false),
                        DatePicker::make('period_until')
                            ->label(__('subscription-system-admin::usage.filters.period_until'))
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
                        ->label(__('subscription-system-admin::usage.actions.reset_usage'))
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning')
                        ->action(function ($record) {
                            $record->update(['used' => 0]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::usage.notifications.usage_reset'))
                                ->success()
                                ->body(__('subscription-system-admin::usage.notifications.usage_reset_message', ['feature' => $record->feature->name]))
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalDescription(__('subscription-system-admin::usage.confirmations.reset_usage')),

                    Action::make('extend_limit')
                        ->label(__('subscription-system-admin::usage.actions.extend_limit'))
                        ->icon('heroicon-o-plus-circle')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('additional_limit')
                                ->label(__('subscription-system-admin::usage.fields.additional_limit'))
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->helperText(__('subscription-system-admin::usage.help.additional_limit')),
                        ])
                        ->action(function ($record, array $data) {
                            if ($record->limit) {
                                $newLimit = $record->limit + $data['additional_limit'];
                                $record->update(['limit' => $newLimit]);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::usage.notifications.limit_extended'))
                                ->success()
                                ->body(__('subscription-system-admin::usage.notifications.limit_extended_message', ['amount' => $data['additional_limit']]))
                                ->send();
                        })
                        ->visible(fn ($record) => $record->limit !== null),

                    DeleteAction::make()
                        ->visible(fn () => false), // Usually don't delete usage records
                ])
                ->label(__('subscription-system-admin::general.actions'))
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
                        ->label(__('subscription-system-admin::usage.bulk_actions.reset_usage'))
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
        return $record->feature?->name . ' - ' . ($record->subscription?->subscribable?->name ?? __('subscription-system-admin::usage.table.no_project'));
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $percentage = $record->limit ? round(($record->used / $record->limit) * 100, 1) : null;
        
        return [
            __('subscription-system-admin::usage.global_search.project') => $record->subscription?->subscribable?->name ?? __('subscription-system-admin::usage.table.no_project'),
            __('subscription-system-admin::usage.global_search.used') => number_format($record->used) . ($record->limit ? '/' . number_format($record->limit) : ''),
            __('subscription-system-admin::usage.global_search.usage') => $percentage ? $percentage . '%' : __('subscription-system-admin::usage.unlimited'),
            __('subscription-system-admin::usage.global_search.period') => $record->period_start ? $record->period_start->format('M Y') : __('subscription-system-admin::usage.global_search.na'),
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