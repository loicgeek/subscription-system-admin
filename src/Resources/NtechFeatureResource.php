<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use NtechServices\SubscriptionSystem\Models\Feature as NtechFeature;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource\Pages;

class NtechFeatureResource extends Resource
{
    protected static ?string $model = NtechFeature::class;

    protected static ?string $table = 'ntech_features';

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
                Tables\Columns\TextColumn::make('description'),

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
