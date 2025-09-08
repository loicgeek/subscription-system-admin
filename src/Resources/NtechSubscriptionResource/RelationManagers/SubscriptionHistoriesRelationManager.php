<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriptionHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'history'; // related to $subscription->history()

    public function form(Form $form): Form
    {
        return $form->schema([

        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('plan.name')->label('Plan'),
                Tables\Columns\TextColumn::make('planPrice.price')
                    ->label('Price')
                    ->formatStateUsing(
                        fn ($state, $record) => optional($record->planPrice)->price && optional($record->planPrice)->currency
                            ? number_format($record->planPrice->price, 0, '.', ' ') . ' ' . strtoupper($record->planPrice->currency)
                            : '—'
                    ),
                Tables\Columns\TextColumn::make('planPrice.billing_cycle')
                    ->label('Billing Cycle')
                    ->formatStateUsing(
                        fn ($state, $record) => optional($record->planPrice)->billing_cycle
                            ? $record->planPrice->billing_cycle
                            : '—'
                    ),

                Tables\Columns\TextColumn::make('created_at')->label('Created At')->date(),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated At')->date(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
