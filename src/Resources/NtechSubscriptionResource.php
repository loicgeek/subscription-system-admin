<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\Pages;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers\SubscriptionFeatureUsagesRelationManager;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers\SubscriptionHistoriesRelationManager;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers\SubscriptionPaymentsRelationManager;
use NtechServices\SubscriptionSystem\Models\Subscription as NtechSubscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NtechSubscriptionResource extends Resource
{
    protected static ?string $model = NtechSubscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Ntech';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
          return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('subscribable.name')->label('Project'),
            Tables\Columns\TextColumn::make('plan.name')->label('Plan'),
            Tables\Columns\TextColumn::make('planPrice.price')->label('Price'),
            Tables\Columns\TextColumn::make('coupon.code')->label('Coupon'),
            Tables\Columns\TextColumn::make('start_date')->date(),
            Tables\Columns\TextColumn::make('next_billing_date')->date(),
            Tables\Columns\TextColumn::make('amount_due'),
            Tables\Columns\TextColumn::make('currency'),
            Tables\Columns\TextColumn::make('status'),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['subscribable']))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SubscriptionFeatureUsagesRelationManager::class,
            SubscriptionHistoriesRelationManager::class,
           // SubscriptionPaymentsRelationManager::class,
          //  RelationManagers\HistoriesRelationManager::class,
           // RelationManagers\UsagesRelationManager::class,
           // RelationManagers\SubscribableRelationManager::class,
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
