<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\Pages;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers\SubscriptionFeatureUsagesRelationManager;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers\SubscriptionHistoriesRelationManager;

use NtechServices\SubscriptionSystem\Models\Subscription as NtechSubscription;
use BackedEnum;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class NtechSubscriptionResource extends Resource
{
    protected static ?string $model = NtechSubscription::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = null;

    protected static string|UnitEnum|null $navigationGroup = 'Ntech-Services';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'id';

    public static function getNavigationLabel(): string
    {
        return __('subscription-system-admin::subscription.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('subscribable_id')
                    ->label(__('subscription-system-admin::subscription.fields.project'))
                    ->relationship('subscribable', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(1),

                Forms\Components\Select::make('plan_id')
                    ->label(__('subscription-system-admin::subscription.fields.plan'))
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(1),

                Forms\Components\Select::make('plan_price_id')
                    ->label(__('subscription-system-admin::subscription.fields.plan_price'))
                    ->relationship('planPrice', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                        $record->currency . ' ' . number_format($record->price, 2) . ' / ' . $record->billing_cycle
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(1),

                Forms\Components\Select::make('coupon_id')
                    ->label(__('subscription-system-admin::subscription.fields.coupon'))
                    ->relationship('coupon', 'code')
                    ->searchable()
                    ->preload()
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('start_date')
                    ->label(__('subscription-system-admin::subscription.fields.start_date'))
                    ->required()
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('next_billing_date')
                    ->label(__('subscription-system-admin::subscription.fields.next_billing_date'))
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\TextInput::make('amount_due')
                    ->label(__('subscription-system-admin::subscription.fields.amount_due'))
                    ->numeric()
                    ->step(0.01)
                    ->prefix(fn ($get) => $get('currency') ?? '$')
                    ->columnSpan(1),

                Forms\Components\Select::make('currency')
                    ->label(__('subscription-system-admin::subscription.fields.currency'))
                    ->options([
                        'USD' => __('subscription-system-admin::currency.usd'),
                        'EUR' => __('subscription-system-admin::currency.eur'),
                        'GBP' => __('subscription-system-admin::currency.gbp'),
                        'CAD' => __('subscription-system-admin::currency.cad'),
                    ])
                    ->required()
                    ->columnSpan(1),

                Forms\Components\Select::make('status')
                    ->label(__('subscription-system-admin::subscription.fields.status'))
                    ->options([
                        'active' => __('subscription-system-admin::subscription.status.active'),
                        'canceled' => __('subscription-system-admin::subscription.status.canceled'),
                        'paused' => __('subscription-system-admin::subscription.status.paused'),
                        'expired' => __('subscription-system-admin::subscription.status.expired'),
                        'pending' => __('subscription-system-admin::subscription.status.pending'),
                        'trial' => __('subscription-system-admin::subscription.status.trial'),
                    ])
                    ->required()
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('trial_ends_at')
                    ->label(__('subscription-system-admin::subscription.fields.trial_ends_at'))
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('canceled_at')
                    ->label(__('subscription-system-admin::subscription.fields.canceled_at'))
                    ->native(false)
                    ->disabled()
                    ->columnSpan(1),

                Forms\Components\Textarea::make('cancellation_reason')
                    ->label(__('subscription-system-admin::subscription.fields.cancellation_reason'))
                    ->rows(3)
                    ->columnSpanFull(),
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
                    ->searchable(),

                TextColumn::make('subscribable.name')
                    ->label(__('subscription-system-admin::subscription.fields.project'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->placeholder(__('subscription-system-admin::subscription.table.no_project')),

                TextColumn::make('plan.name')
                    ->label(__('subscription-system-admin::subscription.fields.plan'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('planPrice.price')
                    ->label(__('subscription-system-admin::subscription.fields.price'))
                    ->formatStateUsing(function ($state, NtechSubscription $record) {
                        return $record->currency . ' ' . number_format($state, 2);
                    })
                    ->sortable()
                    ->alignment(Alignment::Center),

                TextColumn::make('planPrice.billing_cycle')
                    ->label(__('subscription-system-admin::subscription.fields.billing'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __('subscription-system-admin::billing_cycle.' . strtolower($state)))
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'monthly' => 'success',
                        'yearly' => 'warning',
                        'weekly' => 'info',
                        'daily' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('coupon.code')
                    ->label(__('subscription-system-admin::subscription.fields.coupon'))
                    ->badge()
                    ->color('secondary')
                    ->placeholder(__('subscription-system-admin::subscription.table.no_coupon')),

                TextColumn::make('status')
                    ->label(__('subscription-system-admin::subscription.fields.status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __('subscription-system-admin::subscription.status.' . $state))
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'trial' => 'info',
                        'pending' => 'warning',
                        'paused' => 'gray',
                        'canceled' => 'danger',
                        'expired' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('start_date')
                    ->label(__('subscription-system-admin::subscription.table.started'))
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('next_billing_date')
                    ->label(__('subscription-system-admin::subscription.table.next_billing'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(function ($state) {
                        if (!$state) return 'gray';
                        $daysUntil = now()->diffInDays($state, false);
                        if ($daysUntil < 0) return 'danger'; // Overdue
                        if ($daysUntil <= 7) return 'warning'; // Due soon
                        return 'success';
                    }),

                TextColumn::make('amount_due')
                    ->label(__('subscription-system-admin::subscription.fields.amount_due'))
                    ->formatStateUsing(function ($state, NtechSubscription $record) {
                        return $record->currency . ' ' . number_format($state, 2);
                    })
                    ->alignment(Alignment::Center)
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success'),

                TextColumn::make('trial_ends_at')
                    ->label(__('subscription-system-admin::subscription.table.trial_ends'))
                    ->date('d/m/Y')
                    ->placeholder(__('subscription-system-admin::subscription.table.no_trial'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('canceled_at')
                    ->label(__('subscription-system-admin::subscription.table.canceled'))
                    ->date('d/m/Y')
                    ->placeholder(__('subscription-system-admin::subscription.table.active'))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('subscription-system-admin::general.fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('subscription-system-admin::subscription.filters.status'))
                    ->options([
                        'active' => __('subscription-system-admin::subscription.status.active'),
                        'trial' => __('subscription-system-admin::subscription.status.trial'),
                        'pending' => __('subscription-system-admin::subscription.status.pending'),
                        'paused' => __('subscription-system-admin::subscription.status.paused'),
                        'canceled' => __('subscription-system-admin::subscription.status.canceled'),
                        'expired' => __('subscription-system-admin::subscription.status.expired'),
                    ])
                    ->multiple(),

                SelectFilter::make('plan_id')
                    ->label(__('subscription-system-admin::subscription.filters.plan'))
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('subscribable')
                    ->label(__('subscription-system-admin::subscription.filters.project'))
                    ->options(function () {
                        return static::getModel()::with('subscribable')
                            ->get()
                            ->pluck('subscribable.name', 'subscribable.id')
                            ->filter()
                            ->unique()
                            ->toArray();
                    })
                    ->searchable(),

                SelectFilter::make('planPrice.billing_cycle')
                    ->label(__('subscription-system-admin::subscription.filters.billing_cycle'))
                    ->relationship('planPrice', 'billing_cycle')
                    ->options([
                        'DAILY' => __('subscription-system-admin::billing_cycle.daily'),
                        'WEEKLY' => __('subscription-system-admin::billing_cycle.weekly'),
                        'MONTHLY' => __('subscription-system-admin::billing_cycle.monthly'),
                        'YEARLY' => __('subscription-system-admin::billing_cycle.yearly'),
                    ]),

                Filter::make('has_coupon')
                    ->label(__('subscription-system-admin::subscription.filters.with_coupon'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('coupon_id'))
                    ->toggle(),

                Filter::make('overdue')
                    ->label(__('subscription-system-admin::subscription.filters.overdue_billing'))
                    ->query(fn (Builder $query): Builder => 
                        $query->where('next_billing_date', '<', now())
                              ->where('status', 'active')
                    )
                    ->toggle(),

                Filter::make('due_soon')
                    ->label(__('subscription-system-admin::subscription.filters.due_soon'))
                    ->query(fn (Builder $query): Builder => 
                        $query->where('next_billing_date', '<=', now()->addDays(7))
                              ->where('next_billing_date', '>=', now())
                              ->where('status', 'active')
                    )
                    ->toggle(),

                Filter::make('trial_ending')
                    ->label(__('subscription-system-admin::subscription.filters.trial_ending'))
                    ->query(fn (Builder $query): Builder => 
                        $query->where('trial_ends_at', '<=', now()->addDays(3))
                              ->where('trial_ends_at', '>=', now())
                              ->where('status', 'trial')
                    )
                    ->toggle(),

                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label(__('subscription-system-admin::subscription.filters.created_after'))
                            ->native(false),
                        DatePicker::make('created_until')
                            ->label(__('subscription-system-admin::subscription.filters.created_before'))
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn ($query, $date) =>
                                $query->whereDate('created_at', '>=', $date)
                            )
                            ->when($data['created_until'], fn ($query, $date) =>
                                $query->whereDate('created_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                    Action::make('cancel_subscription')
                        ->label(__('subscription-system-admin::subscription.actions.cancel'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('cancellation_reason')
                                ->label(__('subscription-system-admin::subscription.fields.cancellation_reason'))
                                ->required()
                                ->rows(3)
                                ->placeholder(__('subscription-system-admin::subscription.placeholders.cancellation_reason')),
                        ])
                        ->action(function (NtechSubscription $record, array $data) {
                            $record->update([
                                'status' => 'canceled',
                                'canceled_at' => now(),
                                'cancellation_reason' => $data['cancellation_reason'],
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::subscription.notifications.canceled'))
                                ->success()
                                ->body(__('subscription-system-admin::subscription.notifications.subscription_canceled', ['id' => $record->id]))
                                ->send();
                        })
                        ->visible(fn (NtechSubscription $record) => in_array($record->status, ['active', 'trial']))
                        ->requiresConfirmation(),

                    Action::make('pause_subscription')
                        ->label(__('subscription-system-admin::subscription.actions.pause'))
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->action(function (NtechSubscription $record) {
                            $record->update(['status' => 'paused']);
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::subscription.notifications.paused'))
                                ->success()
                                ->body(__('subscription-system-admin::subscription.notifications.subscription_paused', ['id' => $record->id]))
                                ->send();
                        })
                        ->visible(fn (NtechSubscription $record) => $record->status === 'active')
                        ->requiresConfirmation(),

                    Action::make('resume_subscription')
                        ->label(__('subscription-system-admin::subscription.actions.resume'))
                        ->icon('heroicon-o-play')
                        ->color('success')
                        ->action(function (NtechSubscription $record) {
                            $record->update(['status' => 'active']);
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::subscription.notifications.resumed'))
                                ->success()
                                ->body(__('subscription-system-admin::subscription.notifications.subscription_resumed', ['id' => $record->id]))
                                ->send();
                        })
                        ->visible(fn (NtechSubscription $record) => $record->status === 'paused'),

                    Action::make('extend_trial')
                        ->label(__('subscription-system-admin::subscription.actions.extend_trial'))
                        ->icon('heroicon-o-clock')
                        ->color('info')
                        ->form([
                            Forms\Components\TextInput::make('trial_days')
                                ->label(__('subscription-system-admin::subscription.fields.trial_days'))
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->default(7),
                        ])
                        ->action(function (NtechSubscription $record, array $data) {
                            $newTrialEnd = $record->trial_ends_at 
                                ? $record->trial_ends_at->addDays($data['trial_days'])
                                : now()->addDays($data['trial_days']);
                            
                            $record->update(['trial_ends_at' => $newTrialEnd]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::subscription.notifications.trial_extended'))
                                ->success()
                                ->body(__('subscription-system-admin::subscription.notifications.trial_extended_message', ['days' => $data['trial_days']]))
                                ->send();
                        })
                        ->visible(fn (NtechSubscription $record) => $record->status === 'trial'),

                    // DeleteAction::make()
                    //     ->visible(fn () => auth()->user()?->hasRole('super_admin') ?? false),
                ])
                ->label(__('subscription-system-admin::general.actions'))
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
            ])
            ->headerActions([
                // CreateAction::make()
                //     ->label(__('subscription-system-admin::subscription.actions.new_subscription'))
                //     ->icon('heroicon-o-plus'),
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    BulkAction::make('pause_selected')
                        ->label(__('subscription-system-admin::subscription.bulk_actions.pause_selected'))
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->action(fn ($records) =>
                            $records->each(fn ($record) =>
                                $record->update(['status' => 'paused'])
                            )
                        )
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    BulkAction::make('cancel_selected')
                        ->label(__('subscription-system-admin::subscription.bulk_actions.cancel_selected'))
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('cancellation_reason')
                                ->label(__('subscription-system-admin::subscription.fields.cancellation_reason'))
                                ->required()
                                ->rows(3)
                                ->placeholder(__('subscription-system-admin::subscription.placeholders.bulk_cancellation_reason')),
                        ])
                        ->action(fn ($records, array $data) =>
                            $records->each(fn ($record) =>
                                $record->update([
                                    'status' => 'canceled',
                                    'canceled_at' => now(),
                                    'cancellation_reason' => $data['cancellation_reason'],
                                ])
                            )
                        )
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),

                    // DeleteBulkAction::make()
                    //     ->visible(fn () => auth()->user()?->hasRole('super_admin') ?? false),
                ]),
            ])
            ->emptyStateActions([
                // CreateAction::make()
                //     ->label(__('subscription-system-admin::subscription.empty_state.create_first'))
                //     ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['subscribable', 'plan', 'planPrice', 'coupon']))
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            SubscriptionFeatureUsagesRelationManager::class,
            SubscriptionHistoriesRelationManager::class,
            // SubscriptionPaymentsRelationManager::class,
        ];
    }

    // Navigation Badge
    public static function getNavigationBadge(): ?string
    {
        $activeCount = static::getModel()::where('status', 'active')->count();
        return $activeCount > 0 ? (string) $activeCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $overdueCount = static::getModel()::where('next_billing_date', '<', now())
            ->where('status', 'active')
            ->count();
        
        return $overdueCount > 0 ? 'danger' : 'success';
    }

    // Global Search
    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return __('subscription-system-admin::subscription.global_search.title', [
            'id' => $record->id,
            'project' => $record->subscribable?->name ?? __('subscription-system-admin::subscription.table.no_project')
        ]);
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            __('subscription-system-admin::subscription.fields.project') => $record->subscribable?->name ?? __('subscription-system-admin::subscription.table.no_project'),
            __('subscription-system-admin::subscription.fields.plan') => $record->plan?->name ?? __('subscription-system-admin::subscription.global_search.no_plan'),
            __('subscription-system-admin::subscription.fields.status') => __('subscription-system-admin::subscription.status.' . $record->status),
            __('subscription-system-admin::subscription.fields.price') => $record->currency . ' ' . number_format($record->planPrice?->price ?? 0, 2),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNtechSubscriptions::route('/'),
            'create' => Pages\CreateNtechSubscription::route('/create'),
            'edit' => Pages\EditNtechSubscription::route('/{record}/edit'),
        ];
    }
}