<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechSubscriptionResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;

class SubscriptionFeatureUsagesRelationManager extends RelationManager
{
    protected static string $relationship = 'featureUsages'; // Ensure this exists on the Subscription model

    protected static ?string $title = null;

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('subscription-system-admin::subscription.relations.feature_usages.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('subscription-system-admin::general.fields.id'))
                    ->sortable(),
                
                TextColumn::make('feature.name')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.feature'))
                    ->default('—')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('feature.description')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.description'))
                    ->default('—')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                
                TextColumn::make('used')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.used'))
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),
                    
                TextColumn::make('limit')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.limit'))
                    ->state(function (Model $record): string {
                        $limit = $record->limit;
                        
                        if (in_array($limit, ['unlimited', '-1', -1], true)) {
                            return __('subscription-system-admin::subscription.relations.feature_usages.unlimited');
                        }   
                        return number_format((int)$limit);
                    })
                    ->badge()
                    ->color(function (Model $record): string {
                        $limit = $record->limit;                        
                        return in_array($limit, ['unlimited', '-1', -1], true) ? 'success' : 'primary';
                    }),
                
                TextColumn::make('remaining')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.remaining'))
                    ->state(function (Model $record): string {
                        $limit = $record->limit;
                        if (in_array($limit, ['unlimited', '-1', -1], true)) {
                            return __('subscription-system-admin::subscription.relations.feature_usages.unlimited');
                        }
                        $remaining = max(0, (int)$limit - $record->used);
                        return number_format($remaining);
                    })
                    ->badge()
                    ->color(function (Model $record): string {
                        $limit = $record->limit;
                        if (in_array($limit, ['unlimited', '-1', -1], true)) {
                            return 'success';
                        }
                        $percentage = $limit > 0 ? ($record->used / (int)$limit) * 100 : 100;
                        
                        if ($percentage >= 100) return 'danger';
                        if ($percentage >= 80) return 'warning';
                        return 'success';
                    }),
                
                TextColumn::make('usage_percentage')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.usage_percentage'))
                    ->state(function (Model $record): string {
                        $limit = $record->limit;
                        if (in_array($limit, ['unlimited', '-1', -1], true)) {
                            return '0%';
                        }
                        $percentage = $limit > 0 ? round(($record->used / (int)$limit) * 100, 2) : 100;
                        return $percentage . '%';
                    })
                    ->badge()
                    ->color(function (Model $record): string {
                        $limit = $record->limit;
                        if (in_array($limit, ['unlimited', '-1', -1], true)) {
                            return 'gray';
                        }
                        $percentage = $limit > 0 ? ($record->used / (int)$limit) * 100 : 100;
                        if ($percentage >= 100) return 'danger';
                        if ($percentage >= 80) return 'warning';
                        if ($percentage >= 50) return 'primary';
                        return 'success';
                    }),

                TextColumn::make('overage_count')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.overage_count'))
                    ->badge()
                    ->color(function (Model $record): string {
                        return $record->overage_count > 0 ? 'danger' : 'success';
                    }),
                
                TextColumn::make('period_start')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.period_start')),
                    
                TextColumn::make('period_end')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.columns.period_end')),
            ])
            ->defaultSort('feature.name')
            ->filters([
                Filter::make('at_limit')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.filters.at_limit'))
                    ->query(function ($query) {
                        // This is a complex filter, you might want to handle it differently
                        // For now, we'll leave it as a basic filter structure
                        return $query;
                    })
                    ->toggle(),
                    
                Filter::make('unlimited')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.filters.unlimited'))
                    ->query(function ($query) {
                        // Filter for unlimited features - requires custom logic
                        return $query;
                    })
                    ->toggle(),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('reset_usage')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.actions.reset_usage'))
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Model $record) {
                        $record->update(['used' => 0]);
                    })
                    ->visible(fn (Model $record) => $record->used > 0),
            ])
            ->headerActions([
                Action::make('view_all_features')
                    ->label(__('subscription-system-admin::subscription.relations.feature_usages.actions.view_all_features'))
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->url(fn () => route('filament.admin.resources.ntech-subscription-feature-usages.index')),
            ]);
    }
}