<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingCarrierResource\Pages;
use App\Filament\Resources\ShippingCarrierResource\RelationManagers;
use App\Models\ShippingCarrier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingCarrierResource extends Resource
{
    protected static ?string $model = ShippingCarrier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Carrier Name')
                        ->placeholder('e.g. JNE Logistic')
                        ->helperText('The full name of the shipping carrier.')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('service_name')
                        ->label('Service Name')
                        ->placeholder('e.g. JNE Trucking, J&T Freight')
                        ->helperText('The shipping service provided by the carrier.')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('api_key')
                        ->label('API Key')
                        ->placeholder('e.g. JNE-API-KEY-123456')
                        ->helperText('API key used for system integration. Leave empty if not using API.')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('integration_endpoint')
                        ->label('Integration Endpoint')
                        ->placeholder('e.g. https://api.jne.co.id/v1/shipping')
                        ->helperText('The API endpoint used for system communication.')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('cut_off_time')
                        ->label('Cut-Off Time')
                        ->placeholder('e.g. 15:00')
                        ->helperText('The last processing time before daily dispatch.')
                        ->maxLength(10),
                    Forms\Components\TextInput::make('base_rate')
                        ->label('Base Rate')
                        ->placeholder('e.g. 25000')
                        ->helperText('The base shipping rate applied to all shipments.')
                        ->required()
                        ->numeric()
                        ->default(0.00),
                    Forms\Components\TextInput::make('min_weight')
                        ->label('Minimum Weight (Kg)')
                        ->placeholder('e.g. 1.00')
                        ->helperText('Minimum allowed package weight for this service.')
                        ->required()
                        ->numeric()
                        ->default(0.0),
                    Forms\Components\TextInput::make('max_weight')
                        ->label('Maximum Weight (Kg)')
                        ->placeholder('e.g. 120.00')
                        ->helperText('Maximum allowed package weight for this service.')
                        ->numeric(),
                ])->columns(["sm" => 2])->columnSpan(2),
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make("Shipping Status")
                        ->description("Status of the shipping service availability")
                        ->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label('Active')
                                ->helperText('Enable if this carrier is currently in use.')
                                ->required(),
                        ]),
                    Forms\Components\Section::make("Time Stamps")
                        ->description("Information about when the data was created and updated")
                        ->schema([
                            Forms\Components\Placeholder::make("created_at")
                                ->label('Created At')
                                ->content(fn(?ShippingCarrier $record): string => $record ? date_format($record->created_at, "M d, Y") : "-"),
                            Forms\Components\Placeholder::make("updated_at")
                                ->label('Updated At')
                                ->content(fn(?ShippingCarrier $record): string => $record ? date_format($record->updated_at, "M d, Y") : "-"),
                        ]),

                ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->placeholder('No name'),
                Tables\Columns\TextColumn::make('service_name')
                    ->searchable()
                    ->placeholder('No service name'),
                Tables\Columns\TextColumn::make('cut_off_time')
                    ->placeholder('No cut off time'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active Status'),
                Tables\Columns\TextColumn::make('base_rate')
                    ->numeric()
                    ->sortable()
                    ->placeholder('0.0'),
                Tables\Columns\TextColumn::make('min_weight')
                    ->numeric()
                    ->sortable()
                    ->placeholder('0.0')
                    ->suffix(" Kg"),
                Tables\Columns\TextColumn::make('max_weight')
                    ->numeric()
                    ->suffix(" Kg")
                    ->sortable()
                    ->placeholder('0.0'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->emptyStateIcon('heroicon-o-truck')
            ->emptyStateDescription('Create shipping carrier and detail data.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create shipping carrier')
                    ->url(ShippingCarrierResource::getUrl('create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');;
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
            'index' => Pages\ListShippingCarriers::route('/'),
            'create' => Pages\CreateShippingCarrier::route('/create'),
            'edit' => Pages\EditShippingCarrier::route('/{record}/edit'),
        ];
    }
}
