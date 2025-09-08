<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource\Pages;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use NtechServices\SubscriptionSystem\Enums\BillingCycle as NtechBillingCycle;
use NtechServices\SubscriptionSystem\Models\Feature as NtechFeature;
use NtechServices\SubscriptionSystem\Models\Plan as NtechPlan;
use NtechServices\SubscriptionSystem\Models\Coupon as NtechCoupon;

class NtechFeatureResource extends Resource
{
    protected static ?string $model = NtechFeature::class;
    protected static ?string $table = "ntech_features";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Ntech';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required(),
                Forms\Components\TextInput::make('description')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('description')
          
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNtechFeatures::route('/'),
            'create' => Pages\CreateNtechFeature::route('/create'),
            'edit' => Pages\EditNtechFeature::route('/{record}/edit'),
        ];
    }
}
