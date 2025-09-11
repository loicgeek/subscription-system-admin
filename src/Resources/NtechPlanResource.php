<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\Pages;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\RelationManagers;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
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
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

use NtechServices\SubscriptionSystem\Enums\BillingCycle as NtechBillingCycle;
use NtechServices\SubscriptionSystem\Helpers\ConfigHelper;
use NtechServices\SubscriptionSystem\Models\Plan as NtechPlan;

class NtechPlanResource extends Resource
{
    protected static ?string $model = NtechPlan::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = null;

    protected static string|UnitEnum|null $navigationGroup = 'Ntech-Services';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('subscription-system-admin::plan.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Plan Details
                Forms\Components\TextInput::make('name')
                    ->label(__('subscription-system-admin::plan.fields.name'))
                    ->required()
                    ->live()
                    ->maxLength(255)
                    ->placeholder(__('subscription-system-admin::plan.placeholders.name'))
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    })
                    ->columnSpan(1),
                Forms\Components\TextInput::make('slug')
                    ->label(__('subscription-system-admin::plan.fields.slug'))
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label(__('subscription-system-admin::plan.fields.description'))
                    ->rows(2)
                    ->maxLength(500)
                    ->placeholder(__('subscription-system-admin::plan.placeholders.description'))
                    ->columnSpan(1),
                Forms\Components\TextInput::make('order')
                    ->label(__('subscription-system-admin::plan.fields.order'))
                    ->numeric()
                    ->default(0)
                    ->placeholder('0')
                    ->columnSpan(1),
               
                Forms\Components\Toggle::make('popular')
                    ->label(__('subscription-system-admin::plan.fields.popular'))
                    ->default(false)
                    ->helperText(__('subscription-system-admin::plan.help.popular'))
                    ->columnSpan(2),

                // Trial Configuration
                Forms\Components\TextInput::make('trial_value')
                    ->label(__('subscription-system-admin::plan.fields.trial_value'))
                    ->numeric()
                    ->default(0)
                    ->columnSpan(1),
                Forms\Components\Select::make('trial_cycle')
                    ->label(__('subscription-system-admin::plan.fields.trial_cycle'))
                    ->options([
                        NtechBillingCycle::DAILY->value => __('subscription-system-admin::billing_cycle.daily'),
                        NtechBillingCycle::WEEKLY->value => __('subscription-system-admin::billing_cycle.weekly'),
                        NtechBillingCycle::MONTHLY->value => __('subscription-system-admin::billing_cycle.monthly'),
                        NtechBillingCycle::YEARLY->value => __('subscription-system-admin::billing_cycle.yearly'),
                    ])
                    ->required()
                    ->default(NtechBillingCycle::DAILY->value)
                    ->columnSpan(1),

                // Features
                Forms\Components\Repeater::make('features')
                    ->label(__('subscription-system-admin::plan.sections.features'))
                    ->schema([
                        Forms\Components\Select::make('feature_id')
                        ->label(__('subscription-system-admin::feature.singular'))
                        ->options(function () {
                            $featureClass = ConfigHelper::getConfigClass('feature', \NtechServices\SubscriptionSystem\Models\Feature::class);
                            return $featureClass::pluck('name', 'id')->toArray();
                        })
                        ->disableOptionWhen(function ($value, $state, $get) {
                            $allSelectedFeatures = collect($get('../../features'))
                                ->pluck('feature_id')
                                ->filter();
                                
                            $currentSelection = $get('feature_id');
                            
                            return $allSelectedFeatures->contains($value) && $value !== $currentSelection;
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->columnSpan(1),
                            
                        Forms\Components\TextInput::make('value')
                            ->label(__('subscription-system-admin::plan.fields.value'))
                            ->required()
                            ->placeholder(__('subscription-system-admin::plan.placeholders.value'))
                            ->columnSpan(1),
                            
                        Forms\Components\Toggle::make('is_soft_limit')
                            ->label(__('subscription-system-admin::plan.fields.soft_limit'))
                            ->default(false)
                            ->live()
                            ->columnSpan(1),
                            
                        Forms\Components\TextInput::make('overage_price')
                            ->label(__('subscription-system-admin::plan.fields.overage_price'))
                            ->numeric()
                            ->step(0.0001)
                            ->visible(fn ($get) => $get('is_soft_limit'))
                            ->prefix(fn ($get) => match($get('overage_currency')) {
                                'USD' => '$',
                                'EUR' => '€',
                                'GBP' => '£',
                                'CAD' => 'C$',
                                default => '',
                            })
                            ->columnSpan(1),
                            
                        Forms\Components\Select::make('overage_currency')
                            ->label(__('subscription-system-admin::plan.fields.overage_currency'))
                            ->visible(fn ($get) => $get('is_soft_limit'))
                            ->options([
                                'USD' => __('subscription-system-admin::currency.usd'),
                                'EUR' => __('subscription-system-admin::currency.eur'),
                                'GBP' => __('subscription-system-admin::currency.gbp'),
                                'CAD' => __('subscription-system-admin::currency.cad'),
                            ])
                            ->searchable()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->reorderable()
                    ->itemLabel(function (array $state): ?string {
                        if (!isset($state['feature_id'])) {
                            return __('subscription-system-admin::plan.repeater.new_feature');
                        }
                        
                        static $features = null;
                        if ($features === null) {
                            $featureClass = ConfigHelper::getConfigClass('feature', \NtechServices\SubscriptionSystem\Models\Feature::class);
                            $features = $featureClass::pluck('name', 'id')->toArray();
                        }
                        
                        $featureName = $features[$state['feature_id']] ?? __('subscription-system-admin::plan.repeater.unknown_feature');
                        $value = $state['value'] ?? '';
                        
                        return $featureName . ($value ? ": {$value}" : '');
                    })
                    ->addActionLabel(__('subscription-system-admin::plan.actions.add_feature'))
                    ->columnSpanFull(),

                // Prices
                Forms\Components\Repeater::make('planPrices')
                    ->label(__('subscription-system-admin::plan.sections.prices'))
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('currency')
                            ->label(__('subscription-system-admin::plan.fields.currency'))
                            ->options([
                                'USD' => __('subscription-system-admin::currency.usd'),
                                'EUR' => __('subscription-system-admin::currency.eur'),
                                'GBP' => __('subscription-system-admin::currency.gbp'),
                                'CAD' => __('subscription-system-admin::currency.cad'),
                            ])
                            ->required()
                            ->searchable()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('price')
                            ->label(__('subscription-system-admin::plan.fields.price'))
                            ->numeric()
                            ->step(0.01)
                            ->required()
                            ->prefix(fn ($get) => match($get('currency')) {
                                'USD' => '$',
                                'EUR' => '€',
                                'GBP' => '£',
                                'CAD' => 'C$',
                                default => '',
                            })
                            ->columnSpan(1),
                        Forms\Components\Select::make('billing_cycle')
                            ->label(__('subscription-system-admin::plan.fields.billing_cycle'))
                            ->options([
                                NtechBillingCycle::DAILY->value => __('subscription-system-admin::billing_cycle.daily'),
                                NtechBillingCycle::WEEKLY->value => __('subscription-system-admin::billing_cycle.weekly'),
                                NtechBillingCycle::MONTHLY->value => __('subscription-system-admin::billing_cycle.monthly'),
                                NtechBillingCycle::YEARLY->value => __('subscription-system-admin::billing_cycle.yearly'),
                            ])
                            ->required()
                            ->columnSpan(1),

                        // Feature Overrides
                        Forms\Components\Repeater::make('planPriceFeatureOverrides')
                            ->label(__('subscription-system-admin::plan.sections.feature_overrides'))
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('feature_id')
                                    ->label(__('subscription-system-admin::feature.singular'))
                                    ->options(function () {
                                        $featureClass = ConfigHelper::getConfigClass('feature', \NtechServices\SubscriptionSystem\Models\Feature::class);
                                        return $featureClass::pluck('name', 'id')->toArray();
                                    })
                                    ->disableOptionWhen(function ($value, $state, $get) {
                                        return collect($get('../../planPriceFeatureOverrides'))
                                            ->reject(fn ($item) => $item === $state)
                                            ->pluck('feature_id')
                                            ->contains($value);
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('value')
                                    ->label(__('subscription-system-admin::plan.fields.override_value'))
                                    ->required()
                                    ->helperText(__('subscription-system-admin::plan.help.override_value'))
                                    ->columnSpan(1),
                                Forms\Components\Toggle::make('is_soft_limit')
                                    ->label(__('subscription-system-admin::plan.fields.soft_limit'))
                                    ->default(false)
                                    ->live()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('overage_price')
                                    ->label(__('subscription-system-admin::plan.fields.overage_price'))
                                    ->numeric()
                                    ->step(0.0001)
                                    ->visible(fn ($get) => $get('is_soft_limit'))
                                    ->prefix(fn ($get) => match($get('overage_currency')) {
                                        'USD' => '$',
                                        'EUR' => '€',
                                        'GBP' => '£',
                                        'CAD' => 'C$',
                                        default => '',
                                    })
                                    ->columnSpan(1),
                                Forms\Components\Select::make('overage_currency')
                                    ->label(__('subscription-system-admin::plan.fields.overage_currency'))
                                    ->visible(fn ($get) => $get('is_soft_limit'))
                                    ->options([
                                        'USD' => __('subscription-system-admin::currency.usd'),
                                        'EUR' => __('subscription-system-admin::currency.eur'),
                                        'GBP' => __('subscription-system-admin::currency.gbp'),
                                        'CAD' => __('subscription-system-admin::currency.cad'),
                                    ])
                                    ->searchable()
                                    ->columnSpan(1),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(function($state){
                                if (!isset($state['feature_id'])) {
                                    return __('subscription-system-admin::plan.repeater.new_override');
                                }
                                
                                $featureClass = ConfigHelper::getConfigClass('feature', \NtechServices\SubscriptionSystem\Models\Feature::class);
                                $featureName = $featureClass::find($state['feature_id'])?->name;
                                $value = $state['value'] ?? '';
                                
                                return $featureName ? "{$featureName}: {$value}" : __('subscription-system-admin::plan.repeater.new_override');
                            })
                            ->addActionLabel(__('subscription-system-admin::plan.actions.add_feature_override'))
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->itemLabel(function (array $state): ?string {
                        if (!isset($state['currency'], $state['price'])) {
                            return __('subscription-system-admin::plan.repeater.new_price');
                        }
                        
                        $overrideCount = count($state['planPriceFeatureOverrides'] ?? []);
                        $overrideText = $overrideCount > 0 ? " (" . __('subscription-system-admin::plan.repeater.overrides_count', ['count' => $overrideCount]) . ")" : '';
                        
                        return "{$state['currency']} {$state['price']}{$overrideText}";
                    })
                    ->addActionLabel(__('subscription-system-admin::plan.actions.add_price'))
                    ->reorderable()
                    ->cloneable()
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
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label(__('subscription-system-admin::plan.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('order')
                    ->label(__('subscription-system-admin::plan.fields.order'))
                    ->sortable()
                    ->alignment(Alignment::Center),

                IconColumn::make('popular')
                    ->label(__('subscription-system-admin::plan.fields.popular'))
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('')
                    ->trueColor('warning'),

                TextColumn::make('description')
                    ->label(__('subscription-system-admin::plan.fields.description'))
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (NtechPlan $record): ?string {
                        return strlen($record->description ?? '') > 30 ? $record->description : null;
                    }),

                TextColumn::make('trial_value')
                    ->label(__('subscription-system-admin::plan.fields.trial'))
                    ->formatStateUsing(function ($state, NtechPlan $record) {
                        return $state . ' ' . __('subscription-system-admin::billing_cycle.' . strtolower($record->trial_cycle));
                    })
                    ->alignment(Alignment::Center),

                TextColumn::make('features_count')
                    ->label(__('subscription-system-admin::feature.plural'))
                    ->counts('features')
                    ->badge()
                    ->color('info')
                    ->alignment(Alignment::Center),

                TextColumn::make('plan_prices_count')
                    ->label(__('subscription-system-admin::plan.table.prices'))
                    ->counts('planPrices')
                    ->badge()
                    ->color('success')
                    ->alignment(Alignment::Center),

                TextColumn::make('created_at')
                    ->label(__('subscription-system-admin::general.fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('popular')
                    ->label(__('subscription-system-admin::plan.filters.popular')),

                SelectFilter::make('trial_cycle')
                    ->label(__('subscription-system-admin::plan.filters.trial_cycle'))
                    ->options([
                        NtechBillingCycle::DAILY->value => __('subscription-system-admin::billing_cycle.daily'),
                        NtechBillingCycle::WEEKLY->value => __('subscription-system-admin::billing_cycle.weekly'),
                        NtechBillingCycle::MONTHLY->value => __('subscription-system-admin::billing_cycle.monthly'),
                        NtechBillingCycle::YEARLY->value => __('subscription-system-admin::billing_cycle.yearly'),
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                    Action::make('edit_features')
                        ->label(__('subscription-system-admin::plan.actions.manage_features'))
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->color('info')
                        ->url(fn ($record) => route('filament.admin.resources.ntech-plans.edit', [
                            'record' => $record,
                            'activeRelationManager' => 'features',
                        ])),

                    Action::make('edit_prices')
                        ->label(__('subscription-system-admin::plan.actions.manage_prices'))
                        ->icon('heroicon-o-currency-dollar')
                        ->color('warning')
                        ->url(fn ($record) => route('filament.admin.resources.ntech-plans.edit', [
                            'record' => $record,
                            'activeRelationManager' => 'prices',
                        ])),

                    Action::make('toggle_popular')
                        ->label(fn (NtechPlan $record) => $record->popular ? __('subscription-system-admin::plan.actions.remove_popular') : __('subscription-system-admin::plan.actions.mark_popular'))
                        ->icon(fn (NtechPlan $record) => $record->popular ? 'heroicon-o-star' : 'heroicon-s-star')
                        ->color('warning')
                        ->action(function (NtechPlan $record) {
                            $record->update(['popular' => !$record->popular]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title($record->popular ? __('subscription-system-admin::plan.notifications.marked_popular') : __('subscription-system-admin::plan.notifications.unmarked_popular'))
                                ->success()
                                ->body(__('subscription-system-admin::plan.notifications.plan_status_changed', [
                                    'name' => $record->name,
                                    'status' => $record->popular ? __('subscription-system-admin::plan.notifications.marked_popular') : __('subscription-system-admin::plan.notifications.unmarked_popular')
                                ]))
                                ->send();
                        }),

                    Action::make('duplicate')
                        ->label(__('subscription-system-admin::plan.actions.duplicate'))
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->action(function (NtechPlan $record) {
                            $newPlan = $record->replicate(['popular']);
                            $newPlan->name = $record->name . ' ' . __('subscription-system-admin::general.copy_suffix');
                            $newPlan->popular = false;
                            $newPlan->save();

                            // Copy features
                            foreach ($record->features as $feature) {
                                $newPlan->features()->attach($feature->id, [
                                    'value' => $feature->pivot->value,
                                    'is_soft_limit' => $feature->pivot->is_soft_limit,
                                    'overage_price' => $feature->pivot->overage_price,
                                    'overage_currency' => $feature->pivot->overage_currency,
                                ]);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::plan.notifications.duplicated'))
                                ->success()
                                ->body(__('subscription-system-admin::plan.notifications.plan_duplicated', ['name' => $newPlan->name]))
                                ->send();
                        }),

                    DeleteAction::make(),
                ])
                ->label(__('subscription-system-admin::general.actions'))
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('subscription-system-admin::plan.actions.new_plan'))
                    ->icon('heroicon-o-plus'),
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_popular')
                        ->label(__('subscription-system-admin::plan.bulk_actions.mark_popular'))
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->action(fn ($records) => 
                            $records->each(fn ($record) => 
                                $record->update(['popular' => true])
                            )
                        )
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('unmark_popular')
                        ->label(__('subscription-system-admin::plan.bulk_actions.unmark_popular'))
                        ->icon('heroicon-o-star')
                        ->color('gray')
                        ->action(fn ($records) => 
                            $records->each(fn ($record) => 
                                $record->update(['popular' => false])
                            )
                        )
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make()
                    ->label(__('subscription-system-admin::plan.empty_state.create_first'))
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FeatureValuesRelationManager::class,
            RelationManagers\PricesRelationManager::class,
        ];
    }

    // Navigation Badge
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $popularCount = static::getModel()::where('popular', true)->count();
        return $popularCount > 0 ? 'success' : 'primary';
    }

    // Global Search
    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            __('subscription-system-admin::feature.plural') => $record->features()->count(),
            __('subscription-system-admin::plan.table.prices') => $record->planPrices()->count(),
            __('subscription-system-admin::plan.fields.trial') => $record->trial_value . ' ' . __('subscription-system-admin::billing_cycle.' . strtolower($record->trial_cycle)),
            __('subscription-system-admin::plan.fields.popular') => $record->popular ? __('subscription-system-admin::general.yes') : __('subscription-system-admin::general.no'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNtechPlans::route('/'),
            'create' => Pages\CreateNtechPlan::route('/create'),
            'edit' => Pages\EditNtechPlan::route('/{record}/edit'),
        ];
    }
}