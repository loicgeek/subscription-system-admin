<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class PricesRelationManager extends RelationManager
{
    protected static string $relationship = 'planPrices'; // related to $plan->prices()

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('price')->numeric()->required(),
            Forms\Components\TextInput::make('currency')->required(),
            Forms\Components\Select::make('billing_cycle')
                ->options([
                    'monthly' => 'Monthly',
                    'quarterly' => 'Quarterly',
                    'yearly' => 'Yearly',
                ])
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('price'),
            Tables\Columns\TextColumn::make('currency'),
            Tables\Columns\TextColumn::make('billing_cycle'),
        ]);
    }
}
