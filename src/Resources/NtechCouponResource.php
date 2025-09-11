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
use NtechServices\SubscriptionSystem\Models\Coupon as NtechCoupon;

class NtechCouponResource extends Resource
{
    protected static ?string $model = NtechCoupon::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = null;

    protected static string|UnitEnum|null $navigationGroup = 'Ntech-Services';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'code';

    public static function getNavigationLabel(): string
    {
        return __('subscription-system-admin::coupon.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label(__('subscription-system-admin::coupon.fields.code'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder(__('subscription-system-admin::coupon.placeholders.code'))
                    ->columnSpan(1),
                Forms\Components\TextInput::make('discount_amount')
                    ->label(__('subscription-system-admin::coupon.fields.discount_amount'))
                    ->numeric()
                    ->required()
                    ->placeholder(__('subscription-system-admin::coupon.placeholders.discount_amount'))
                    ->columnSpan(1),
                Forms\Components\Select::make('discount_type')
                    ->label(__('subscription-system-admin::coupon.fields.discount_type'))
                    ->options([
                        'fixed' => __('subscription-system-admin::coupon.discount_types.fixed'),
                        'percentage' => __('subscription-system-admin::coupon.discount_types.percentage')
                    ])
                    ->required()
                    ->default('percentage')
                    ->columnSpan(1),
                Forms\Components\DateTimePicker::make('expires_at')
                    ->label(__('subscription-system-admin::coupon.fields.expires_at'))
                    ->native(false)
                    ->placeholder(__('subscription-system-admin::coupon.placeholders.expires_at'))
                    ->columnSpan(1),
                Forms\Components\TextInput::make('usage_limit')
                    ->label(__('subscription-system-admin::coupon.fields.usage_limit'))
                    ->numeric()
                    ->placeholder(__('subscription-system-admin::coupon.placeholders.usage_limit'))
                    ->columnSpan(1),
                Forms\Components\TextInput::make('used_count')
                    ->label(__('subscription-system-admin::coupon.fields.used_count'))
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->columnSpan(1),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('subscription-system-admin::coupon.fields.active'))
                    ->default(true)
                    ->columnSpan(1),
                Forms\Components\Textarea::make('description')
                    ->label(__('subscription-system-admin::coupon.fields.description'))
                    ->rows(3)
                    ->placeholder(__('subscription-system-admin::coupon.placeholders.description'))
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label(__('subscription-system-admin::coupon.fields.code'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->copyable()
                    ->copyMessage(__('subscription-system-admin::coupon.messages.code_copied')),

                TextColumn::make('discount_amount')
                    ->label(__('subscription-system-admin::coupon.table.discount'))
                    ->sortable()
                    ->formatStateUsing(function ($state, NtechCoupon $record) {
                        return $record->discount_type === 'percentage' 
                            ? $state . '%' 
                            : '$' . number_format($state, 2);
                    }),

                TextColumn::make('discount_type')
                    ->label(__('subscription-system-admin::coupon.table.type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __('subscription-system-admin::coupon.discount_types.' . $state))
                    ->color(fn (string $state): string => match ($state) {
                        'fixed' => 'success',
                        'percentage' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('usage_limit')
                    ->label(__('subscription-system-admin::coupon.fields.usage_limit'))
                    ->placeholder(__('subscription-system-admin::coupon.table.unlimited'))
                    ->alignment(Alignment::Center),

                TextColumn::make('used_count')
                    ->label(__('subscription-system-admin::coupon.table.used'))
                    ->numeric()
                    ->sortable()
                    ->alignment(Alignment::Center),

                IconColumn::make('is_active')
                    ->label(__('subscription-system-admin::coupon.fields.active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('expires_at')
                    ->label(__('subscription-system-admin::coupon.table.expires'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(function ($state) {
                        if (!$state) return 'gray';
                        return now()->gt($state) ? 'danger' : 'success';
                    })
                    ->placeholder(__('subscription-system-admin::coupon.table.never')),

                TextColumn::make('created_at')
                    ->label(__('subscription-system-admin::general.fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('discount_type')
                    ->label(__('subscription-system-admin::coupon.filters.discount_type'))
                    ->options([
                        'fixed' => __('subscription-system-admin::coupon.discount_types.fixed'),
                        'percentage' => __('subscription-system-admin::coupon.discount_types.percentage'),
                    ]),

                TernaryFilter::make('is_active')
                    ->label(__('subscription-system-admin::coupon.filters.active_status')),

                Filter::make('expired')
                    ->label(__('subscription-system-admin::coupon.filters.expired'))
                    ->query(fn (Builder $query): Builder => 
                        $query->whereNotNull('expires_at')->where('expires_at', '<', now())
                    )
                    ->toggle(),

                Filter::make('expires_soon')
                    ->label(__('subscription-system-admin::coupon.filters.expires_soon'))
                    ->query(fn (Builder $query): Builder => 
                        $query->whereNotNull('expires_at')
                              ->where('expires_at', '>', now())
                              ->where('expires_at', '<', now()->addDays(7))
                    )
                    ->toggle(),

                Filter::make('expires_at')
                    ->schema([
                        DatePicker::make('expires_from')
                            ->label(__('subscription-system-admin::coupon.filters.expires_after'))
                            ->native(false),
                        DatePicker::make('expires_until')
                            ->label(__('subscription-system-admin::coupon.filters.expires_before'))
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
                        ->label(fn (NtechCoupon $record) => $record->is_active ? __('subscription-system-admin::coupon.actions.deactivate') : __('subscription-system-admin::coupon.actions.activate'))
                        ->icon(fn (NtechCoupon $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (NtechCoupon $record) => $record->is_active ? 'danger' : 'success')
                        ->action(function (NtechCoupon $record) {
                            $record->update(['is_active' => !$record->is_active]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title($record->is_active ? __('subscription-system-admin::coupon.notifications.activated') : __('subscription-system-admin::coupon.notifications.deactivated'))
                                ->success()
                                ->body(__('subscription-system-admin::coupon.notifications.status_changed', [
                                    'code' => $record->code,
                                    'status' => $record->is_active ? __('subscription-system-admin::coupon.notifications.activated') : __('subscription-system-admin::coupon.notifications.deactivated')
                                ]))
                                ->send();
                        }),
                    
                    Action::make('duplicate')
                        ->label(__('subscription-system-admin::coupon.actions.duplicate'))
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->action(function (NtechCoupon $record) {
                            $newCoupon = $record->replicate();
                            $newCoupon->code = $record->code . '_' . __('subscription-system-admin::coupon.copy_suffix');
                            $newCoupon->used_count = 0;
                            $newCoupon->save();
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::coupon.notifications.duplicated'))
                                ->success()
                                ->body(__('subscription-system-admin::coupon.notifications.coupon_duplicated', ['code' => $newCoupon->code]))
                                ->send();
                        }),
                    
                    DeleteAction::make(),
                ])
                ->label(__('subscription-system-admin::general.actions'))
                ->color('primary')
                ->icon('heroicon-m-ellipsis-vertical')
                ->button(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('subscription-system-admin::coupon.actions.new_coupon'))
                    ->icon('heroicon-o-plus'),
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label(__('subscription-system-admin::coupon.bulk_actions.activate_selected'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => 
                            $records->each(fn ($record) => 
                                $record->update(['is_active' => true])
                            )
                        )
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('deactivate')
                        ->label(__('subscription-system-admin::coupon.bulk_actions.deactivate_selected'))
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
                    ->label(__('subscription-system-admin::coupon.empty_state.create_first'))
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
            __('subscription-system-admin::coupon.global_search.type') => __('subscription-system-admin::coupon.discount_types.' . $record->discount_type),
            __('subscription-system-admin::coupon.global_search.discount') => $record->discount_type === 'percentage' 
                ? $record->discount_amount . '%' 
                : '$' . number_format($record->discount_amount, 2),
            __('subscription-system-admin::coupon.global_search.status') => $record->is_active ? __('subscription-system-admin::coupon.global_search.active') : __('subscription-system-admin::coupon.global_search.inactive'),
            __('subscription-system-admin::coupon.global_search.used') => $record->used_count . ($record->usage_limit ? '/' . $record->usage_limit : ''),
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