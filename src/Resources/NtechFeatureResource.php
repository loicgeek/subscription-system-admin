<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources;

use NtechServices\SubscriptionSystemAdmin\Resources\NtechFeatureResource\Pages;
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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

use NtechServices\SubscriptionSystem\Enums\BillingCycle as NtechBillingCycle;
use NtechServices\SubscriptionSystem\Models\Feature as NtechFeature;
use NtechServices\SubscriptionSystem\Models\Plan as NtechPlan;
use NtechServices\SubscriptionSystem\Models\Coupon as NtechCoupon;

class NtechFeatureResource extends Resource
{
    protected static ?string $model = NtechFeature::class;
    
    protected static ?string $table = "ntech_features";

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = null;

    protected static string|UnitEnum|null $navigationGroup = 'Ntech-Services';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationLabel(): string
    {
        return __('subscription-system-admin::feature.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('subscription-system-admin::feature.fields.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('subscription-system-admin::feature.placeholders.name'))
                    ->columnSpan(1),
                Forms\Components\TextInput::make('slug')
                    ->label(__('subscription-system-admin::feature.fields.slug'))
                    ->maxLength(255)
                    ->placeholder(__('subscription-system-admin::feature.placeholders.slug'))
                    ->helperText(__('subscription-system-admin::feature.help.slug'))
                    ->columnSpan(1),
                Forms\Components\Textarea::make('description')
                    ->label(__('subscription-system-admin::feature.fields.description'))
                    ->required()
                    ->rows(3)
                    ->placeholder(__('subscription-system-admin::feature.placeholders.description'))
                    ->columnSpanFull(),
               
                Forms\Components\TextInput::make('default_value')
                    ->label(__('subscription-system-admin::feature.fields.default_value'))
                    ->placeholder(__('subscription-system-admin::feature.placeholders.default_value'))
                    ->helperText(__('subscription-system-admin::feature.help.default_value'))
                    ->columnSpan(1),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('subscription-system-admin::feature.fields.active'))
                    ->default(true)
                    ->helperText(__('subscription-system-admin::feature.help.active'))
                    ->columnSpan(1),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('subscription-system-admin::feature.fields.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('slug')
                    ->label(__('subscription-system-admin::feature.fields.slug'))
                    ->searchable()
                    ->color('gray')
                    ->copyable()
                    ->copyMessage(__('subscription-system-admin::feature.messages.slug_copied'))
                    ->toggleable(),

                TextColumn::make('description')
                    ->label(__('subscription-system-admin::feature.fields.description'))
                    ->limit(50)
                    ->tooltip(function (NtechFeature $record): ?string {
                        return strlen($record->description) > 50 ? $record->description : null;
                    }),

                TextColumn::make('default_value')
                    ->label(__('subscription-system-admin::feature.table.default'))
                    ->placeholder(__('subscription-system-admin::feature.table.none'))
                    ->alignment(Alignment::Center),

                IconColumn::make('is_active')
                    ->label(__('subscription-system-admin::feature.fields.active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label(__('subscription-system-admin::general.fields.created_at'))
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('subscription-system-admin::feature.filters.active_status')),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    
                    Action::make('toggle_active')
                        ->label(fn (NtechFeature $record) => $record->is_active ? __('subscription-system-admin::feature.actions.deactivate') : __('subscription-system-admin::feature.actions.activate'))
                        ->icon(fn (NtechFeature $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (NtechFeature $record) => $record->is_active ? 'danger' : 'success')
                        ->action(function (NtechFeature $record) {
                            $record->update(['is_active' => !$record->is_active]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title($record->is_active ? __('subscription-system-admin::feature.notifications.activated') : __('subscription-system-admin::feature.notifications.deactivated'))
                                ->success()
                                ->body(__('subscription-system-admin::feature.notifications.status_changed', [
                                    'name' => $record->name,
                                    'status' => $record->is_active ? __('subscription-system-admin::feature.notifications.activated') : __('subscription-system-admin::feature.notifications.deactivated')
                                ]))
                                ->send();
                        }),
                    
                    Action::make('duplicate')
                        ->label(__('subscription-system-admin::feature.actions.duplicate'))
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->action(function (NtechFeature $record) {
                            $newFeature = $record->replicate();
                            $newFeature->name = $record->name . ' ' . __('subscription-system-admin::general.copy_suffix');
                            $newFeature->slug = $record->slug ? $record->slug . '-copy' : null;
                            $newFeature->save();
                            
                            \Filament\Notifications\Notification::make()
                                ->title(__('subscription-system-admin::feature.notifications.duplicated'))
                                ->success()
                                ->body(__('subscription-system-admin::feature.notifications.feature_duplicated', ['name' => $newFeature->name]))
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
                    ->label(__('subscription-system-admin::feature.actions.new_feature'))
                    ->icon('heroicon-o-plus'),
            ])
            ->groupedBulkActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label(__('subscription-system-admin::feature.bulk_actions.activate_selected'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => 
                            $records->each(fn ($record) => 
                                $record->update(['is_active' => true])
                            )
                        )
                        ->deselectRecordsAfterCompletion(),
                    
                    BulkAction::make('deactivate')
                        ->label(__('subscription-system-admin::feature.bulk_actions.deactivate_selected'))
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
                    ->label(__('subscription-system-admin::feature.empty_state.create_first'))
                    ->icon('heroicon-o-plus'),
            ])
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
        $inactiveCount = static::getModel()::where('is_active', false)->count();
        return $inactiveCount > 0 ? 'warning' : 'success';
    }

    // Global Search
    public static function getGlobalSearchResultTitle(\Illuminate\Database\Eloquent\Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            __('subscription-system-admin::feature.global_search.status') => $record->is_active ? __('subscription-system-admin::feature.global_search.active') : __('subscription-system-admin::feature.global_search.inactive'),
            __('subscription-system-admin::feature.global_search.default') => $record->default_value ?? __('subscription-system-admin::feature.table.none'),
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