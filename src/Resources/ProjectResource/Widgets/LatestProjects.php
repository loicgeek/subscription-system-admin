<?php

namespace NtechServices\SubscriptionSystemAdmin\Resources\ProjectResource\Widgets;

use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestProjects extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => Project::query()
                    ->latest('created_at')
                    ->limit(5),
            )
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('owner.name'),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Team Members'),
                TextColumn::make('active')
                    ->label('Active')
,
                TextColumn::make('created_at')
                    ->dateTime()
            ])
            ->defaultSort('created_at', 'desc') ->searchable(false)  // disables search input
            ->paginated(false);  //
    }
}
