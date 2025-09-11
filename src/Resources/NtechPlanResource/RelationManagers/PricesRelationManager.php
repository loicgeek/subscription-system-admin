<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'planPrices'; // related to $plan->prices()

    protected static ?string $title = null;

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('subscription-system-admin::plan.relations.prices.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('price')
                ->label(__('subscription-system-admin::plan.relations.prices.fields.price'))
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('currency')
                ->label(__('subscription-system-admin::plan.relations.prices.fields.currency'))
                ->required(),
            Forms\Components\Select::make('billing_cycle')
                ->label(__('subscription-system-admin::plan.relations.prices.fields.billing_cycle'))
                ->options([
                    'monthly' => __('subscription-system-admin::plan.relations.prices.billing_cycles.monthly'),
                    'quarterly' => __('subscription-system-admin::plan.relations.prices.billing_cycles.quarterly'),
                    'yearly' => __('subscription-system-admin::plan.relations.prices.billing_cycles.yearly'),
                ])
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('price')
                ->label(__('subscription-system-admin::plan.relations.prices.columns.price')),
            Tables\Columns\TextColumn::make('currency')
                ->label(__('subscription-system-admin::plan.relations.prices.columns.currency')),
            Tables\Columns\TextColumn::make('billing_cycle')
                ->label(__('subscription-system-admin::plan.relations.prices.columns.billing_cycle')),
        ]);
    }
}