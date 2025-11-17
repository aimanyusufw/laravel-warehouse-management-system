<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Filament\Resources\LocationResource\RelationManagers;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Placeholder::make('qr_code_path')
                        ->content(function ($record): HtmlString {
                            if (! $record) {
                                return new HtmlString("
                                                <div class='p-2 flex flex-col items-center justify-center h-32 w-32 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 text-gray-500 text-sm'>
                                                    <span>No data available</span>
                                                    </div>
                                            ");
                            }
                            return new HtmlString("
                                                <img 
                                                    src='" . asset('storage/' . $record->qr_code_path) . "' 
                                                    alt='QR Code Preview' 
                                                    class='h-32 w-32 object-contain rounded-lg border border-gray-200 shadow-sm'
                                                >
                                            ");
                        }),
                    Forms\Components\TextInput::make('name')
                        ->placeholder('Zone A - R01 - L01')
                        ->helperText('Enter the location name or identifier.')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\Select::make('type')
                        ->options([
                            'picking' => 'Picking',
                            'bulk' => 'Bulk',
                            'qc' => 'QC',
                            'staging' => 'Staging',
                        ])
                        ->placeholder('Select type')
                        ->helperText('Select the storage or operational type.')
                        ->native(false)
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('capacity')
                        ->placeholder('100')
                        ->helperText('Enter the maximum capacity for this location.')
                        ->numeric()
                        ->required()
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('aisle')
                        ->placeholder('A01')
                        ->helperText('Enter the aisle identifier (e.g., A01).')
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('rack')
                        ->placeholder('R01')
                        ->helperText('Enter the rack identifier (e.g., R01).')
                        ->maxLength(255)
                        ->columnSpan(1),
                    Forms\Components\TextInput::make('level')
                        ->placeholder('1')
                        ->helperText('Enter the level number (e.g., 1 for the bottom shelf).')
                        ->numeric()
                        ->columnSpan(1),
                ])->columns(["sm" => 2])->columnSpan(2),
                Forms\Components\Section::make("Time Stamps")
                    ->description("Details of when data was changed and also created")
                    ->schema([
                        Forms\Components\Placeholder::make("created_at")
                            ->content(fn(?Location $record): string => $record ? date_format($record->created_at, "M d, Y") : "-"),
                        Forms\Components\Placeholder::make("updated_at")
                            ->content(fn(?Location $record): string => $record ? date_format($record->updated_at, "M d, Y") : "-"),
                    ])
                    ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Location Name')
                    ->searchable()
                    ->placeholder('No name'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->placeholder('No type'),

                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacity')
                    ->numeric()
                    ->sortable()
                    ->placeholder('0'),

                Tables\Columns\TextColumn::make('aisle')
                    ->label('Aisle')
                    ->searchable()
                    ->placeholder('No aisle'),

                Tables\Columns\TextColumn::make('rack')
                    ->label('Rack')
                    ->searchable()
                    ->placeholder('No rack'),

                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->numeric()
                    ->sortable()
                    ->placeholder('0'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('Not set'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('Not updated'),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Deleted At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('Active'),

            ])
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateDescription('Create locations and detail data.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create locations')
                    ->url(LocationResource::getUrl('create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}
