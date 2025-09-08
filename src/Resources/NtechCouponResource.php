<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use NtechServices\SubscriptionSystem\Models\Coupon as NtechCoupon;
use NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource\Pages;

class NtechCouponResource extends Resource
{
    protected static ?string $model = NtechCoupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Ntech';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')->required(),
            Forms\Components\TextInput::make('discount_amount')->numeric()->required(),
            Forms\Components\Select::make('discount_type')
                ->options(['fixed' => 'Fixed', 'percentage' => 'Percentage'])
                ->required(),
            Forms\Components\DateTimePicker::make('expires_at'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code'),
            Tables\Columns\TextColumn::make('discount_amount'),
            Tables\Columns\TextColumn::make('discount_type'),
            Tables\Columns\TextColumn::make('expires_at')->dateTime(),
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
            'index' => Pages\ListNtechCoupons::route('/'),
            'create' => Pages\CreateNtechCoupon::route('/create'),
            'edit' => Pages\EditNtechCoupon::route('/{record}/edit'),
        ];
    }
}
