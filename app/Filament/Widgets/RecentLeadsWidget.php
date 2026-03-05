<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentLeadsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->heading('Son leadler')
            ->defaultPaginationPageOption(10)
            ->paginated([10])
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->since()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tip')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'quote' ? 'Teklif' : 'Iletisim'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Durum')
                    ->badge(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Ad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company')
                    ->label('Sirket')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('Atanan')
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Ac')
                    ->url(fn (Lead $record): string => route('filament.admin.resources.leads.edit', ['record' => $record]))
                    ->icon('heroicon-o-arrow-top-right-on-square'),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return Lead::query()
            ->with('assignee')
            ->latest('created_at');
    }
}
