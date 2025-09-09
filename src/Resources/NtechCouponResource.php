<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechCouponResource\Pages;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

use NtechServices\SubscriptionSystem\Enums\BillingCycle as NtechBillingCycle;
use NtechServices\SubscriptionSystem\Models\Feature as NtechFeature;
use NtechServices\SubscriptionSystem\Models\Plan as NtechPlan;
use NtechServices\SubscriptionSystem\Models\Coupon as NtechCoupon;

class NtechCouponResource extends Resource
{
    protected static ?string $model = NtechCoupon::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Coupons';

    protected static string|UnitEnum|null $navigationGroup = 'Ntech';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Coupon Code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('SAVE20')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('discount_amount')
                    ->label('Discount Amount')
                    ->numeric()
                    ->required()
                    ->placeholder('10.00 or 20')
                    ->columnSpan(1),
                Forms\Components\Select::make('discount_type')
                    ->label('Discount Type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percentage' => 'Percentage'
                    ])
                    ->required()
                    ->default('percentage')
                    ->columnSpan(1),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Expires At')
                    ->native(false)
                    ->placeholder('Select expiry date')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('usage_limit')
                    ->label('Usage Limit')
                    ->numeric()
                    ->placeholder('Leave empty for unlimited')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('used_count')
                    ->label('Times Used')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->columnSpan(1),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->columnSpan(1),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->placeholder('Optional description for internal use')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->copyable()
                    ->copyMessage('Coupon code copied!'),

                TextColumn::make('discount_amount')
                    ->label('Discount')
                    ->sortable()
                    ->formatStateUsing(function ($state, NtechCoupon $record) {
                        return $record->discount_type === 'percentage' 
                            ? $state . '%' 
                            : '$' . number_format($state, 2);
                    }),

                TextColumn::make('discount_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'fixed' => 'success',
                        'percentage' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('usage_limit')
                    ->label('Usage Limit')
                    ->placeholder('Unlimited')
                    ->alignment(Alignment::Center),

                TextColumn::make('used_count')
                    ->label('Used')
                    ->numeric()
                    ->sortable()
                    ->alignment(Alignment::Center),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(function ($state) {
                        if (!$state) return 'gray';
                        return now()->gt($state) ? 'danger' : 'success';
                    })
                    ->placeholder('Never'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('discount_type')
                    ->label('Discount Type')
                    ->options([
                        'fixed' => 'Fixed Amount',
                        'percentage' => 'Percentage',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Filter::make('expired')
                    ->label('Expired Coupons')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereNotNull('expires_at')->where('expires_at', '<', now())
                    )
                    ->toggle(),

                Filter::make('expires_soon')
                    ->label('Expires in 7 days')
                    ->query(fn (Builder $query): Builder => 
                        $query->whereNotNull('expires_at')
                              ->where('expires_at', '>', now())
                              ->where('expires_at', '<', now()->addDays(7))
                    )
                    ->toggle(),

                Filter::make('expires_at')
                    ->schema([
                        DatePicker::make('expires_from')
                            ->label('Expires After')
                            ->native(false),
                        DatePicker::make('expires_until')
                            ->label('Expires Before')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['expires_from'], fn ($query, $date) => 
                                $query->whereDate('expires_at', '>=', $date)
                            )
                            ->when($data['expires_until'], fn ($query, $date) => 
                                $query->whereDate('expires_at', '<=', $date)
                            );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    
                    Action::make('toggle_status')
                        ->label(fn (NtechCoupon $record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (NtechCoupon $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (NtechCoupon $record) => $record->is_active ? 'danger' : 'success')
                        ->action(function (NtechCoupon $record) {
                            $record->update(['is_active' => !$record->is_active]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title($record->is_active ? 'Coupon activated' : 'Coupon deactivated')
                                ->success()
                                ->body("Coupon '{$record->code}' has been " . ($record->is_active ? 'activated' : 'deactivated') . ".")
                                ->send();
                        }),
                    
                    Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->action(function (NtechCoupon $record) {
                            $newCoupon = $record->replicate();
                            $newCoupon->code = $record->code . '_COPY';
                            $newCoupon->used_count = 0;
                            $newCoupon->save();
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Coupon duplicated')
                                ->success()
                                ->body("New coupon '{$newCoupon->code}' has been created.")
                                ->send();
                        }),
                    
                    DeleteAction::make(),
                ])
                ->label('Actions')
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('New Coupon')
                    ->icon('heroicon-o-plus'),
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => 
                            $records->each(fn ($record) => 
                                $record->update(['is_active' => true])
                            )
                        )
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => 
                            $records->each(fn ($record) => 
                                $record->update(['is_active' => false])
                            )
                        )
                        ->deselectRecordsAfterCompletion(),
                    
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                CreateAction::make()
                    ->label('Create your first coupon')
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Navigation Badge
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $expiredCount = static::getModel()::whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->where('is_active', true)
            ->count();
        
        return $expiredCount > 0 ? 'danger' : 'success';
    }

    // Global Search
    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->code;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Type' => ucfirst($record->discount_type),
            'Discount' => $record->discount_type === 'percentage' 
                ? $record->discount_amount . '%' 
                : '$' . number_format($record->discount_amount, 2),
            'Status' => $record->is_active ? 'Active' : 'Inactive',
            'Used' => $record->used_count . ($record->usage_limit ? '/' . $record->usage_limit : ''),
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