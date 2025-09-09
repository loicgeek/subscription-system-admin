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

   
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                
                TextColumn::make('feature.name')
                    ->label('Feature')
                    ->default('—')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('feature.description')
                    ->label('Description')
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
                    ->label('Used')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format($state)),
                    
                TextColumn::make('limit')
                    ->label('Limit')
                    ->state(function (Model $record): string {
                        $limit = $record->limit;
                        
                        if (in_array($limit, ['unlimited', '-1', -1], true)) {
                            return 'Unlimited';
                        }   
                        return number_format((int)$limit);
                    })
                    ->badge()
                    ->color(function (Model $record): string {
                        $limit = $record->limit;                        
                        return in_array($limit, ['unlimited', '-1', -1], true) ? 'success' : 'primary';
                    }),
                
                TextColumn::make('remaining')
                    ->label('Remaining')
                    ->state(function (Model $record): string {
                        $limit = $record->limit;
                        if (in_array($limit, ['unlimited', '-1', -1], true)) {
                            return 'Unlimited';
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
                    ->label('Usage %')
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
                    ->label("Overage count")
                    ->badge()
                    ->color(function (Model $record): string {
                        return $record->overage_count > 0 ? 'danger' : 'success';
                    })
                    ,
                
               
                    TextColumn::make('period_start')
                    ->label("Period start")
                  //  ->dateTime()
                   ,
                    TextColumn::make('period_end')
                    ->label("Period end"),
                  //  ->dateTime()
                    
              
                    
            ])
            ->defaultSort('feature.name')
            ->filters([
                Filter::make('at_limit')
                    ->label('At Limit')
                    ->query(function ($query) {
                        // This is a complex filter, you might want to handle it differently
                        // For now, we'll leave it as a basic filter structure
                        return $query;
                    })
                    ->toggle(),
                    
                Filter::make('unlimited')
                    ->label('Unlimited Features')
                    ->query(function ($query) {
                        // Filter for unlimited features - requires custom logic
                        return $query;
                    })
                    ->toggle(),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('reset_usage')
                    ->label('Reset Usage')
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
                    ->label('View All Features Summary')
                    ->icon('heroicon-o-chart-bar')
                    ->color('info')
                    ->url(fn () => route('filament.admin.resources.ntech-subscription-feature-usages.index')),
            ]);
    }
}