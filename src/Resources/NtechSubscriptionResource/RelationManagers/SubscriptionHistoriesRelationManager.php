<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers;

use Filament\Schemas\Schema;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class SubscriptionHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'history'; // related to $subscription->history()

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
        
        ->columns([
            TextColumn::make('id')->label('ID'),
            TextColumn::make('status')->label('Status'),
            TextColumn::make('plan.name')->label('Plan'),
            TextColumn::make('planPrice.price')
            ->label('Price')
            ->formatStateUsing(fn ($state, $record) => 
                optional($record->planPrice)->price && optional($record->planPrice)->currency
                    ? number_format($record->planPrice->price, 0, '.', ' ') . ' ' . strtoupper($record->planPrice->currency)
                    : '—'
            ),
            TextColumn::make('planPrice.billing_cycle')
            ->label('Billing Cycle')
            ->formatStateUsing(fn ($state, $record) => 
                optional($record->planPrice)->billing_cycle
                    ? $record->planPrice->billing_cycle
                    : '—'
            ),
        

            TextColumn::make('created_at')->label('Created At')->date(),
            TextColumn::make('updated_at')->label('Updated At')->date(),
        ])
        ->defaultSort('created_at', 'desc');
    }
}