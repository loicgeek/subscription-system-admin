<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class FeatureValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'features'; // related to $plan->features()

    protected static ?string $title = null;

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('subscription-system-admin::plan.relations.feature_values.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\Select::make('feature_id')
                ->label(__('subscription-system-admin::plan.relations.feature_values.fields.feature'))
                ->relationship('feature', 'name')
                ->required(),
            Forms\Components\TextInput::make('value')
                ->label(__('subscription-system-admin::plan.relations.feature_values.fields.value'))
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->label(__('subscription-system-admin::plan.relations.feature_values.columns.feature')),
            Tables\Columns\TextColumn::make('description')
                ->label(__('subscription-system-admin::plan.relations.feature_values.columns.description')),
            Tables\Columns\TextColumn::make('pivot.value')
                ->label(__('subscription-system-admin::plan.relations.feature_values.columns.value')),
        ]);
    }
}