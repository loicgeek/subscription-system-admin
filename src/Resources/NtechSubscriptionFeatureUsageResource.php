<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use NtechServices\SubscriptionSystem\Models\SubscriptionFeatureUsage as NtechSubscriptionFeatureUsage;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionFeatureUsageResource\Pages;

class NtechSubscriptionFeatureUsageResource extends Resource
{
    protected static ?string $model = NtechSubscriptionFeatureUsage::class;

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
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('subscription.subscribable.name')->label('Project'),
                TextColumn::make('feature.name')->label('Feature'),
                TextColumn::make('used')->label('Used'),
                TextColumn::make('limit')->label('Limit'),

                TextColumn::make('period_start')->label('Period Start')->dateTime(),
                TextColumn::make('period_end')->label('Period End')->dateTime(),
                TextColumn::make('created_at')->label('Created At')->dateTime(),
                TextColumn::make('updated_at')->label('Updated At')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
           // ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['subscription.subscribable', 'feature']))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNtechSubscriptionFeatureUsages::route('/'),
            'create' => Pages\CreateNtechSubscriptionFeatureUsage::route('/create'),
            'edit' => Pages\EditNtechSubscriptionFeatureUsage::route('/{record}/edit'),
        ];
    }
}
