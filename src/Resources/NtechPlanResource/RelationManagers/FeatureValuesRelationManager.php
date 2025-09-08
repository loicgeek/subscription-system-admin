<?php 



namespace NtechServices\SubscriptionSystemAdmin\Resources\NtechPlanResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class FeatureValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'features'; // related to $plan->features()

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('feature_id')
                ->relationship('feature', 'name')
                ->required(),
            Forms\Components\TextInput::make('value')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->label('Feature'),
            Tables\Columns\TextColumn::make('description')->label('Description'),
            Tables\Columns\TextColumn::make('value'),
        ]);
    }
}
