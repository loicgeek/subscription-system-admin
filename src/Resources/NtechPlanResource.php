<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use NtechServices\SubscriptionSystem\Enums\BillingCycle as NtechBillingCycle;
use NtechServices\SubscriptionSystem\Models\Plan as NtechPlan;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\Pages;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\RelationManagers;

class NtechPlanResource extends Resource
{
    protected static ?string $model = NtechPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Ntech';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Plan Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('description')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('trial_value')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('trial_cycle')
                            ->options([
                                NtechBillingCycle::DAILY->value => NtechBillingCycle::DAILY->name,
                                NtechBillingCycle::WEEKLY->value => NtechBillingCycle::WEEKLY->name,
                                NtechBillingCycle::MONTHLY->value => NtechBillingCycle::MONTHLY->name,
                                NtechBillingCycle::YEARLY->value => NtechBillingCycle::YEARLY->name,
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Features')
                    ->schema([

                        Forms\Components\Repeater::make('features')
                            ->schema([
                                Forms\Components\Select::make('id')
                                    ->label('Feature')
                                    ->options(function () {
                                        $featureClass = \NtechServices\SubscriptionSystem\Models\Feature::class;

                                        return $featureClass::pluck('name', 'id')->toArray();
                                    })
                                    ->disableOptionWhen(function ($value, $state, $get) {
                                        // Prevent selecting the same feature multiple times
                                        return collect($get('../../features'))
                                            ->reject(fn ($item) => $item === $state)
                                            ->pluck('id')
                                            ->contains($value);
                                    })
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('value')
                                    ->label('Value')
                                    ->required(),
                                Forms\Components\Toggle::make('is_soft_limit')
                                    ->label('Soft Limit')
                                    ->columnSpan(2)
                                    ->default(false)
                                    ->live(), // or ->reactive() for older versions

                                Forms\Components\TextInput::make('overage_price')
                                    ->label('Price per additional item')
                                    ->numeric()
                                    ->step(0.0001)
                                    ->required()
                                    ->hidden(fn ($get) => ! $get('is_soft_limit'))
                                    ->prefix(fn ($get) => match ($get('overage_currency')) {
                                        'USD' => '$',
                                        'EUR' => '€',
                                        'GBP' => '£',
                                        'CAD' => 'C$',
                                        default => '',
                                    }),

                                Forms\Components\Select::make('overage_currency')
                                    ->label('Currency')
                                    ->hidden(fn ($get) => ! $get('is_soft_limit'))
                                    ->options([
                                        'USD' => 'USD ($)',
                                        'EUR' => 'EUR (€)',
                                        'GBP' => 'GBP (£)',
                                        'CAD' => 'CAD (C$)',
                                    ])
                                    ->required()
                                    ->searchable(),

                            ])
                            ->columns(2)
                            ->collapsible()
                            ->reorderable()
                            ->itemLabel(
                                fn (array $state): ?string => isset($state['id'])
                                    ? \NtechServices\SubscriptionSystem\Models\Feature::class::find($state['id'])?->name
                                    : 'New Feature'
                            )
                            ->addActionLabel('Add Feature'),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Prices')
                    ->schema([
                        Forms\Components\Repeater::make('planPrices')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\Select::make('currency')
                                            ->label('Currency')
                                            ->options([
                                                'USD' => 'USD ($)',
                                                'EUR' => 'EUR (€)',
                                                'GBP' => 'GBP (£)',
                                                'CAD' => 'CAD (C$)',
                                                // Add more currencies as needed
                                            ])
                                            ->required()
                                            ->searchable(),
                                        Forms\Components\TextInput::make('price')
                                            ->label('Price')
                                            ->numeric()
                                            ->step(0.01)
                                            ->required()
                                            ->prefix(fn ($get) => match ($get('currency')) {
                                                'USD' => '$',
                                                'EUR' => '€',
                                                'GBP' => '£',
                                                'CAD' => 'C$',
                                                default => '',
                                            }),
                                        Forms\Components\Select::make('billing_cycle')
                                            ->label('Billing Cycle')
                                            ->options([
                                                NtechBillingCycle::DAILY->value => NtechBillingCycle::DAILY->name,
                                                NtechBillingCycle::WEEKLY->value => NtechBillingCycle::WEEKLY->name,
                                                NtechBillingCycle::MONTHLY->value => NtechBillingCycle::MONTHLY->name,
                                                NtechBillingCycle::YEARLY->value => NtechBillingCycle::YEARLY->name,
                                            ])
                                            ->required(),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true),
                                    ]),

                                Forms\Components\Section::make('Feature Overrides')
                                    ->schema([
                                        Forms\Components\Repeater::make('planPriceFeatureOverrides')
                                            ->relationship()
                                            ->schema([
                                                Forms\Components\Select::make('feature_id')
                                                    ->label('Feature')
                                                    ->options(function () {
                                                        $featureClass = \NtechServices\SubscriptionSystem\Models\Feature::class;

                                                        return $featureClass::pluck('name', 'id')->toArray();
                                                    })
                                                    ->disableOptionWhen(function ($value, $state, $get) {
                                                        // Prevent selecting the same feature multiple times within this price
                                                        return collect($get('../../planPriceFeatureOverrides'))
                                                            ->reject(fn ($item) => $item === $state)
                                                            ->pluck('feature_id')
                                                            ->contains($value);
                                                    })
                                                    ->required()
                                                    ->searchable()
                                                    ->preload(),

                                                Forms\Components\TextInput::make('value')
                                                    ->label('Override Value')
                                                    ->required()
                                                    ->helperText('This value will override the default plan feature value for this specific price.'),

                                                Forms\Components\Toggle::make('is_soft_limit')
                                                    ->label('Soft Limit')
                                                    ->default(false)
                                                    ->live()
                                                    ->columnSpanFull(),

                                                Forms\Components\TextInput::make('overage_price')
                                                    ->label('Overage Price')
                                                    ->numeric()
                                                    ->step(0.0001)
                                                    ->hidden(fn ($get) => ! $get('is_soft_limit'))
                                                    ->prefix(fn ($get) => match ($get('overage_currency')) {
                                                        'USD' => '$',
                                                        'EUR' => '€',
                                                        'GBP' => '£',
                                                        'CAD' => 'C$',
                                                        default => '',
                                                    }),

                                                Forms\Components\Select::make('overage_currency')
                                                    ->label('Overage Currency')
                                                    ->hidden(fn ($get) => ! $get('is_soft_limit'))
                                                    ->options([
                                                        'USD' => 'USD ($)',
                                                        'EUR' => 'EUR (€)',
                                                        'GBP' => 'GBP (£)',
                                                        'CAD' => 'CAD (C$)',
                                                    ])
                                                    ->searchable(),
                                            ])
                                            ->columns(2)
                                            ->collapsible()
                                            ->collapsed()
                                            ->itemLabel(function ($state) {
                                                if (! isset($state['feature_id'])) {
                                                    return 'New Override';
                                                }

                                                $featureClass = \NtechServices\SubscriptionSystem\Models\Feature::class;
                                                $featureName = $featureClass::find($state['feature_id'])?->name;
                                                $value = $state['value'] ?? '';

                                                return $featureName ? "{$featureName}: {$value}" : 'New Override';
                                            })

                                            ->addActionLabel('Add Feature Override')
                                            ->reorderable(false),
                                    ])
                                    ->collapsible()
                                    ->collapsed()
                                    ->compact(),
                            ])
                            ->collapsible()
                            ->itemLabel(function (array $state): ?string {
                                if (! isset($state['currency'], $state['price'])) {
                                    return 'New Price';
                                }

                                $overrideCount = count($state['planPriceFeatureOverrides'] ?? []);
                                $overrideText = $overrideCount > 0 ? " ({$overrideCount} overrides)" : '';

                                return "{$state['currency']} {$state['price']}{$overrideText}";
                            })
                            ->addActionLabel('Add Price')
                            ->reorderable()
                            ->cloneable(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\IconColumn::make('popular')->boolean(),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('billing_cycle')->searchable(),
                Tables\Columns\TextColumn::make('trial_value')->searchable(),
                Tables\Columns\TextColumn::make('trial_cycle')->searchable(),
                Tables\Columns\TextColumn::make('features_count')
                    ->label('Features')
                    ->counts('features')
                    ->badge(),
                Tables\Columns\TextColumn::make('planPrices_count')
                    ->label('Prices')
                    ->counts('planPrices')
                    ->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('edit_features')
                    ->label('Features')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->url(fn ($record) => route('filament.admin.resources.ntech-plans.edit', [
                        'record' => $record,
                        'activeRelationManager' => 'features',
                    ])),

                Tables\Actions\Action::make('edit_prices')
                    ->label('Prices')
                    ->icon('heroicon-o-currency-dollar')
                    ->url(fn ($record) => route('filament.admin.resources.ntech-plans.edit', [
                        'record' => $record,
                        'activeRelationManager' => 'prices',
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FeatureValuesRelationManager::class,
            RelationManagers\PricesRelationManager::class,
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
