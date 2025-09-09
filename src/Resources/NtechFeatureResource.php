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

    protected static ?string $navigationLabel = 'Features';

    protected static string|UnitEnum|null $navigationGroup = 'Ntech';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Feature Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('API Access')
                    ->columnSpan(1),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->maxLength(255)
                    ->placeholder('api-access')
                    ->helperText('Unique identifier for the feature')
                    ->columnSpan(1),
                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3)
                    ->placeholder('Access to our powerful API with unlimited requests')
                    ->columnSpanFull(),
               
                Forms\Components\TextInput::make('default_value')
                    ->label('Default Value')
                    ->placeholder('0, false, or empty')
                    ->helperText('Default value when not included in a plan')
                    ->columnSpan(1),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Whether this feature is available for assignment')
                    ->columnSpan(1),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->color('gray')
                    ->copyable()
                    ->copyMessage('Feature slug copied!')
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (NtechFeature $record): ?string {
                        return strlen($record->description) > 50 ? $record->description : null;
                    }),


                TextColumn::make('default_value')
                    ->label('Default')
                    ->placeholder('None')
                    ->alignment(Alignment::Center),


                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

              

               

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                TernaryFilter::make('is_active')
                    ->label('Active Status'),

              
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    
                    Action::make('toggle_active')
                        ->label(fn (NtechFeature $record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (NtechFeature $record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                        ->color(fn (NtechFeature $record) => $record->is_active ? 'danger' : 'success')
                        ->action(function (NtechFeature $record) {
                            $record->update(['is_active' => !$record->is_active]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title($record->is_active ? 'Feature activated' : 'Feature deactivated')
                                ->success()
                                ->body("Feature '{$record->name}' has been " . ($record->is_active ? 'activated' : 'deactivated') . ".")
                                ->send();
                        }),
                    

                    
                    Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('info')
                        ->action(function (NtechFeature $record) {
                            $newFeature = $record->replicate();
                            $newFeature->name = $record->name . ' (Copy)';
                            $newFeature->slug = $record->slug ? $record->slug . '-copy' : null;
                            $newFeature->save();
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Feature duplicated')
                                ->success()
                                ->body("New feature '{$newFeature->name}' has been created.")
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
                    ->label('New Feature')
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
                    ->label('Create your first feature')
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
            'Status' => $record->is_active ? 'Active' : 'Inactive',
            'Default' => $record->default_value ?? 'None',
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