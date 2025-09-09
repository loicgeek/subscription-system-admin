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

    protected static ?string $navigationLabel = 'Subscriptions';

    protected static string|UnitEnum|null $navigationGroup = 'Ntech-Services';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('subscribable_id')
                    ->label('Project')
                    ->relationship('subscribable', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(1),

                Forms\Components\Select::make('plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(1),

                Forms\Components\Select::make('plan_price_id')
                    ->label('Plan Price')
                    ->relationship('planPrice', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                        $record->currency . ' ' . number_format($record->price, 2) . ' / ' . $record->billing_cycle
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpan(1),

                Forms\Components\Select::make('coupon_id')
                    ->label('Coupon')
                    ->relationship('coupon', 'code')
                    ->searchable()
                    ->preload()
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('next_billing_date')
                    ->label('Next Billing Date')
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\TextInput::make('amount_due')
                    ->label('Amount Due')
                    ->numeric()
                    ->step(0.01)
                    ->prefix(fn ($get) => $get('currency') ?? '$')
                    ->columnSpan(1),

                Forms\Components\Select::make('currency')
                    ->label('Currency')
                    ->options([
                        'USD' => 'USD ($)',
                        'EUR' => 'EUR (€)',
                        'GBP' => 'GBP (£)',
                        'CAD' => 'CAD (C$)',
                    ])
                    ->required()
                    ->columnSpan(1),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'canceled' => 'Canceled',
                        'paused' => 'Paused',
                        'expired' => 'Expired',
                        'pending' => 'Pending',
                        'trial' => 'Trial',
                    ])
                    ->required()
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('trial_ends_at')
                    ->label('Trial Ends At')
                    ->native(false)
                    ->columnSpan(1),

                Forms\Components\DatePicker::make('canceled_at')
                    ->label('Canceled At')
                    ->native(false)
                    ->disabled()
                    ->columnSpan(1),

                Forms\Components\Textarea::make('cancellation_reason')
                    ->label('Cancellation Reason')
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
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('subscribable.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->placeholder('No project'),

                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('planPrice.price')
                    ->label('Price')
                    ->formatStateUsing(function ($state, NtechSubscription $record) {
                        return $record->currency . ' ' . number_format($state, 2);
                    })
                    ->sortable()
                    ->alignment(Alignment::Center),

                TextColumn::make('planPrice.billing_cycle')
                    ->label('Billing')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(strtolower($state)))
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'monthly' => 'success',
                        'yearly' => 'warning',
                        'weekly' => 'info',
                        'daily' => 'primary',
                        default => 'gray',
                    }),

                TextColumn::make('coupon.code')
                    ->label('Coupon')
                    ->badge()
                    ->color('secondary')
                    ->placeholder('No coupon'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
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
                    ->label('Started')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('next_billing_date')
                    ->label('Next Billing')
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
                    ->label('Amount Due')
                    ->formatStateUsing(function ($state, NtechSubscription $record) {
                        return $record->currency . ' ' . number_format($state, 2);
                    })
                    ->alignment(Alignment::Center)
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success'),

                TextColumn::make('trial_ends_at')
                    ->label('Trial Ends')
                    ->date('d/m/Y')
                    ->placeholder('No trial')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('canceled_at')
                    ->label('Canceled')
                    ->date('d/m/Y')
                    ->placeholder('Active')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'trial' => 'Trial',
                        'pending' => 'Pending',
                        'paused' => 'Paused',
                        'canceled' => 'Canceled',
                        'expired' => 'Expired',
                    ])
                    ->multiple(),

                SelectFilter::make('plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('subscribable')
                    ->label('Project')
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
                    ->label('Billing Cycle')
                    ->relationship('planPrice', 'billing_cycle')
                    ->options([
                        'DAILY' => 'Daily',
                        'WEEKLY' => 'Weekly',
                        'MONTHLY' => 'Monthly',
                        'YEARLY' => 'Yearly',
                    ]),

                Filter::make('has_coupon')
                    ->label('With Coupon')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('coupon_id'))
                    ->toggle(),

                Filter::make('overdue')
                    ->label('Overdue Billing')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('next_billing_date', '<', now())
                              ->where('status', 'active')
                    )
                    ->toggle(),

                Filter::make('due_soon')
                    ->label('Due in 7 days')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('next_billing_date', '<=', now()->addDays(7))
                              ->where('next_billing_date', '>=', now())
                              ->where('status', 'active')
                    )
                    ->toggle(),

                Filter::make('trial_ending')
                    ->label('Trial Ending Soon')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('trial_ends_at', '<=', now()->addDays(3))
                              ->where('trial_ends_at', '>=', now())
                              ->where('status', 'trial')
                    )
                    ->toggle(),

                Filter::make('date_range')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label('Created After')
                            ->native(false),
                        DatePicker::make('created_until')
                            ->label('Created Before')
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
                        ->label('Cancel')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('cancellation_reason')
                                ->label('Cancellation Reason')
                                ->required()
                                ->rows(3)
                                ->placeholder('Please provide a reason for cancellation'),
                        ])
                        ->action(function (NtechSubscription $record, array $data) {
                            $record->update([
                                'status' => 'canceled',
                                'canceled_at' => now(),
                                'cancellation_reason' => $data['cancellation_reason'],
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Subscription canceled')
                                ->success()
                                ->body("Subscription #{$record->id} has been canceled.")
                                ->send();
                        })
                        ->visible(fn (NtechSubscription $record) => in_array($record->status, ['active', 'trial']))
                        ->requiresConfirmation(),

                    Action::make('pause_subscription')
                        ->label('Pause')
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->action(function (NtechSubscription $record) {
                            $record->update(['status' => 'paused']);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Subscription paused')
                                ->success()
                                ->body("Subscription #{$record->id} has been paused.")
                                ->send();
                        })
                        ->visible(fn (NtechSubscription $record) => $record->status === 'active')
                        ->requiresConfirmation(),

                    Action::make('resume_subscription')
                        ->label('Resume')
                        ->icon('heroicon-o-play')
                        ->color('success')
                        ->action(function (NtechSubscription $record) {
                            $record->update(['status' => 'active']);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Subscription resumed')
                                ->success()
                                ->body("Subscription #{$record->id} has been resumed.")
                                ->send();
                        })
                        ->visible(fn (NtechSubscription $record) => $record->status === 'paused'),

                    Action::make('extend_trial')
                        ->label('Extend Trial')
                        ->icon('heroicon-o-clock')
                        ->color('info')
                        ->form([
                            Forms\Components\TextInput::make('trial_days')
                                ->label('Additional Trial Days')
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
                                ->title('Trial extended')
                                ->success()
                                ->body("Trial extended by {$data['trial_days']} days.")
                                ->send();
                        })
                        ->visible(fn (NtechSubscription $record) => $record->status === 'trial'),

                    // DeleteAction::make()
                    //     ->visible(fn () => auth()->user()?->hasRole('super_admin') ?? false),
                ])
                ->label('Actions')
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
            ])
            ->headerActions([
                // CreateAction::make()
                //     ->label('New Subscription')
                //     ->icon('heroicon-o-plus'),
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    BulkAction::make('pause_selected')
                        ->label('Pause Selected')
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
                        ->label('Cancel Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->form([
                            Forms\Components\Textarea::make('cancellation_reason')
                                ->label('Cancellation Reason')
                                ->required()
                                ->rows(3)
                                ->placeholder('Please provide a reason for bulk cancellation'),
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
                //     ->label('Create your first subscription')
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
        return 'Subscription #' . $record->id . ' - ' . ($record->subscribable?->name ?? 'No Project');
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Project' => $record->subscribable?->name ?? 'No Project',
            'Plan' => $record->plan?->name ?? 'No Plan',
            'Status' => ucfirst($record->status),
            'Price' => $record->currency . ' ' . number_format($record->planPrice?->price ?? 0, 2),
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