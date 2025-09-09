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

    protected static ?string $navigationLabel = 'Plans';

    protected static string|UnitEnum|null $navigationGroup = 'Ntech-Services';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                // Plan Details
                Forms\Components\TextInput::make('name')
                    ->label('Plan Name')
                    ->required()
                    ->live()
                    ->maxLength(255)
                    ->placeholder('Basic Plan')
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    })
                    ->columnSpan(1),
                    Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(2)
                    ->maxLength(500)
                    ->placeholder('Perfect for small teams and startups')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('order')
                    ->label('Display Order')
                    ->numeric()
                    ->default(0)
                    ->placeholder('0')
                    ->columnSpan(1),
               
                Forms\Components\Toggle::make('popular')
                    ->label('Mark as Popular')
                    ->default(false)
                    ->helperText('This plan will be highlighted in pricing tables')
                    ->columnSpan(2),

                // Trial Configuration
                Forms\Components\TextInput::make('trial_value')
                    ->label('Trial Duration')
                    ->numeric()
                    ->default(0)
                    ->columnSpan(1),
                Forms\Components\Select::make('trial_cycle')
                    ->label('Trial Cycle')
                    ->options([
                        NtechBillingCycle::DAILY->value => NtechBillingCycle::DAILY->name,
                        NtechBillingCycle::WEEKLY->value => NtechBillingCycle::WEEKLY->name,
                        NtechBillingCycle::MONTHLY->value => NtechBillingCycle::MONTHLY->name,
                        NtechBillingCycle::YEARLY->value => NtechBillingCycle::YEARLY->name,
                    ])
                    ->required()
                    ->default(NtechBillingCycle::DAILY->value)
                    ->columnSpan(1),

                // Features
                // Features - Manual handling (this works reliably)
                Forms\Components\Repeater::make('features')
                    ->label('Plan Features')
                    // NO ->relationship() here
                    ->schema([
                        Forms\Components\Select::make('feature_id')
                        ->label('Feature')
                        ->options(function () {
                            $featureClass = ConfigHelper::getConfigClass('feature', \NtechServices\SubscriptionSystem\Models\Feature::class);
                            return $featureClass::pluck('name', 'id')->toArray();
                        })
                        ->disableOptionWhen(function ($value, $state, $get) {
                            $allSelectedFeatures = collect($get('../../features'))
                                ->pluck('feature_id')
                                ->filter(); // Remove empty values
                                
                            $currentSelection = $get('feature_id');
                            
                            // Only disable if the value is selected elsewhere (not in current field)
                            return $allSelectedFeatures->contains($value) && $value !== $currentSelection;
                        })
                        ->required()
                        ->searchable()
                        ->preload()
                        ->columnSpan(1),
                            
                        Forms\Components\TextInput::make('value') // Direct field, no pivot prefix
                            ->label('Value')
                            ->required()
                            ->placeholder('100, true, unlimited')
                            ->columnSpan(1),
                            
                        Forms\Components\Toggle::make('is_soft_limit') // Direct field, no pivot prefix
                            ->label('Soft Limit')
                            ->default(false)
                            ->live()
                            ->columnSpan(1),
                            
                        Forms\Components\TextInput::make('overage_price') // Direct field, no pivot prefix
                            ->label('Overage Price')
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
                            
                        Forms\Components\Select::make('overage_currency') // Direct field, no pivot prefix
                            ->label('Overage Currency')
                            ->visible(fn ($get) => $get('is_soft_limit'))
                            ->options([
                                'USD' => 'USD ($)',
                                'EUR' => 'EUR (€)',
                                'GBP' => 'GBP (£)',
                                'CAD' => 'CAD (C$)',
                            ])
                            ->searchable()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->reorderable()
                    ->itemLabel(function (array $state): ?string {
                        if (!isset($state['feature_id'])) {
                            return 'New Feature';
                        }
                        
                        // Cache features to avoid repeated queries
                        static $features = null;
                        if ($features === null) {
                            $featureClass = ConfigHelper::getConfigClass('feature', \NtechServices\SubscriptionSystem\Models\Feature::class);
                            $features = $featureClass::pluck('name', 'id')->toArray();
                        }
                        
                        $featureName = $features[$state['feature_id']] ?? 'Unknown Feature';
                        $value = $state['value'] ?? '';
                        
                        return $featureName . ($value ? ": {$value}" : '');
                    })
                    ->addActionLabel('Add Feature')
                    ->columnSpanFull(),

                // Prices
                Forms\Components\Repeater::make('planPrices')
                    ->label('Plan Prices')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'EUR' => 'EUR (€)',
                                'GBP' => 'GBP (£)',
                                'CAD' => 'CAD (C$)',
                            ])
                            ->required()
                            ->searchable()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('price')
                            ->label('Price')
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
                            ->label('Billing Cycle')
                            ->options([
                                NtechBillingCycle::DAILY->value => NtechBillingCycle::DAILY->name,
                                NtechBillingCycle::WEEKLY->value => NtechBillingCycle::WEEKLY->name,
                                NtechBillingCycle::MONTHLY->value => NtechBillingCycle::MONTHLY->name,
                                NtechBillingCycle::YEARLY->value => NtechBillingCycle::YEARLY->name,
                            ])
                            ->required()
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpan(1),

                        // Feature Overrides
                        Forms\Components\Repeater::make('planPriceFeatureOverrides')
                            ->label('Feature Overrides')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('feature_id')
                                    ->label('Feature')
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
                                    ->label('Override Value')
                                    ->required()
                                    ->helperText('This will override the plan default value')
                                    ->columnSpan(1),
                                Forms\Components\Toggle::make('is_soft_limit')
                                    ->label('Soft Limit')
                                    ->default(false)
                                    ->live()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('overage_price')
                                    ->label('Overage Price')
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
                                    ->label('Overage Currency')
                                    ->visible(fn ($get) => $get('is_soft_limit'))
                                    ->options([
                                        'USD' => 'USD ($)',
                                        'EUR' => 'EUR (€)',
                                        'GBP' => 'GBP (£)',
                                        'CAD' => 'CAD (C$)',
                                    ])
                                    ->searchable()
                                    ->columnSpan(1),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(function($state){
                                if (!isset($state['feature_id'])) {
                                    return 'New Override';
                                }
                                
                                $featureClass = ConfigHelper::getConfigClass('feature', \NtechServices\SubscriptionSystem\Models\Feature::class);
                                $featureName = $featureClass::find($state['feature_id'])?->name;
                                $value = $state['value'] ?? '';
                                
                                return $featureName ? "{$featureName}: {$value}" : 'New Override';
                            })
                            ->addActionLabel('Add Feature Override')
                            ->reorderable(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->itemLabel(function (array $state): ?string {
                        if (!isset($state['currency'], $state['price'])) {
                            return 'New Price';
                        }
                        
                        $overrideCount = count($state['planPriceFeatureOverrides'] ?? []);
                        $overrideText = $overrideCount > 0 ? " ({$overrideCount} overrides)" : '';
                        
                        return "{$state['currency']} {$state['price']}{$overrideText}";
                    })
                    ->addActionLabel('Add Price')
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
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('order')
                    ->label('Order')
                    ->sortable()
                    ->alignment(Alignment::Center),

                IconColumn::make('popular')
                    ->label('Popular')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('')
                    ->trueColor('warning'),

                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (NtechPlan $record): ?string {
                        return strlen($record->description ?? '') > 30 ? $record->description : null;
                    }),

                TextColumn::make('trial_value')
                    ->label('Trial')
                    ->formatStateUsing(function ($state, NtechPlan $record) {
                        return $state . ' ' . strtolower($record->trial_cycle);
                    })
                    ->alignment(Alignment::Center),

                TextColumn::make('features_count')
                    ->label('Features')
                    ->counts('features')
                    ->badge()
                    ->color('info')
                    ->alignment(Alignment::Center),

                TextColumn::make('planPrices_count')
                    ->label('Prices')
                    ->counts('planPrices')
                    ->badge()
                    ->color('success')
                    ->alignment(Alignment::Center),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('popular')
                    ->label('Popular Plans'),

                SelectFilter::make('trial_cycle')
                    ->label('Trial Cycle')
                    ->options([
                        NtechBillingCycle::DAILY->value => NtechBillingCycle::DAILY->name,
                        NtechBillingCycle::WEEKLY->value => NtechBillingCycle::WEEKLY->name,
                        NtechBillingCycle::MONTHLY->value => NtechBillingCycle::MONTHLY->name,
                        NtechBillingCycle::YEARLY->value => NtechBillingCycle::YEARLY->name,
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),

                    Action::make('edit_features')
                        ->label('Manage Features')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->color('info')
                        ->url(fn ($record) => route('filament.admin.resources.ntech-plans.edit', [
                            'record' => $record,
                            'activeRelationManager' => 'features',
                        ])),

                    Action::make('edit_prices')
                        ->label('Manage Prices')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('warning')
                        ->url(fn ($record) => route('filament.admin.resources.ntech-plans.edit', [
                            'record' => $record,
                            'activeRelationManager' => 'prices',
                        ])),

                    Action::make('toggle_popular')
                        ->label(fn (NtechPlan $record) => $record->popular ? 'Remove Popular' : 'Mark as Popular')
                        ->icon(fn (NtechPlan $record) => $record->popular ? 'heroicon-o-star' : 'heroicon-s-star')
                        ->color('warning')
                        ->action(function (NtechPlan $record) {
                            $record->update(['popular' => !$record->popular]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title($record->popular ? 'Plan marked as popular' : 'Plan unmarked as popular')
                                ->success()
                                ->body("Plan '{$record->name}' has been " . ($record->popular ? 'marked as popular' : 'unmarked as popular') . ".")
                                ->send();
                        }),

                    Action::make('duplicate')
                        ->label('Duplicate Plan')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->action(function (NtechPlan $record) {
                            $newPlan = $record->replicate(['popular']);
                            $newPlan->name = $record->name . ' (Copy)';
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
                                ->title('Plan duplicated')
                                ->success()
                                ->body("New plan '{$newPlan->name}' has been created.")
                                ->send();
                        }),

                    DeleteAction::make(),
                ])
                ->label('Actions')
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Plan')
                    ->icon('heroicon-o-plus'),
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    BulkAction::make('mark_popular')
                        ->label('Mark as Popular')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->action(fn ($records) => 
                            $records->each(fn ($record) => 
                                $record->update(['popular' => true])
                            )
                        )
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('unmark_popular')
                        ->label('Remove Popular')
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
                    ->label('Create your first plan')
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
            'Features' => $record->features()->count(),
            'Prices' => $record->planPrices()->count(),
            'Trial' => $record->trial_value . ' ' . strtolower($record->trial_cycle),
            'Popular' => $record->popular ? 'Yes' : 'No',
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