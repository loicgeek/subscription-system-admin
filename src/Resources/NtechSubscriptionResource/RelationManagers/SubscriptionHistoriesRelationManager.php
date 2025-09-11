<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class SubscriptionHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'history'; // related to $subscription->history()

    protected static ?string $title = null;

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('subscription-system-admin::subscription.relations.histories.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('subscription-system-admin::general.fields.id')),
                    
                TextColumn::make('status')
                    ->label(__('subscription-system-admin::subscription.relations.histories.columns.status')),
                    
                TextColumn::make('plan.name')
                    ->label(__('subscription-system-admin::subscription.relations.histories.columns.plan')),
                    
                TextColumn::make('planPrice.price')
                    ->label(__('subscription-system-admin::subscription.relations.histories.columns.price'))
                    ->formatStateUsing(fn ($state, $record) => 
                        optional($record->planPrice)->price && optional($record->planPrice)->currency
                            ? number_format($record->planPrice->price, 0, '.', ' ') . ' ' . strtoupper($record->planPrice->currency)
                            : '—'
                    ),
                    
                TextColumn::make('planPrice.billing_cycle')
                    ->label(__('subscription-system-admin::subscription.relations.histories.columns.billing_cycle'))
                    ->formatStateUsing(fn ($state, $record) => 
                        optional($record->planPrice)->billing_cycle
                            ? $record->planPrice->billing_cycle
                            : '—'
                    ),
    
                TextColumn::make('created_at')
                    ->label(__('subscription-system-admin::general.fields.created_at'))
                    ->date(),
                    
                TextColumn::make('updated_at')
                    ->label(__('subscription-system-admin::general.fields.updated_at'))
                    ->date(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}