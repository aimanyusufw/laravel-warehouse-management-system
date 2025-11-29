<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionReceiptResource\Pages;
use App\Filament\Resources\ProductionReceiptResource\RelationManagers;
use App\Models\ProductionReceipt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductionReceiptResource extends Resource
{
    protected static ?string $model = ProductionReceipt::class;

    protected static ?string $navigationGroup = 'Inbound';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make('Main Receipt Information')
                        ->schema([
                            Forms\Components\TextInput::make('receipt_number')
                                ->required()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true)
                                ->label('Receipt Number'),

                            Forms\Components\DatePicker::make('date')
                                ->required()
                                ->default(now())
                                ->label('Receipt Date'),
                        ])->columns(2),
                    Forms\Components\Section::make('Received Item Details')
                        ->description('List all products received from the production line.')
                        ->schema([
                            Forms\Components\Repeater::make('details')
                                ->relationship('details')
                                ->schema([
                                    Forms\Components\Select::make('product_id')
                                        ->label("Product")
                                        ->relationship('product', 'sku')
                                        ->searchable()
                                        ->required()
                                        ->preload()
                                        ->columnSpan(3),
                                    Forms\Components\TextInput::make('batch_number')
                                        ->maxLength(255)
                                        ->required()
                                        ->columnSpan(2)
                                        ->label('Batch/Lot Number'),
                                    Forms\Components\TextInput::make('quantity_received')
                                        ->numeric()
                                        ->minValue(1)
                                        ->required()
                                        ->columnSpan(2)
                                        ->label('Quantity Received'),
                                    Forms\Components\Select::make('qc_status')
                                        ->options([
                                            'Pending' => 'Pending',
                                            'Passed' => 'QC Passed',
                                            'Failed' => 'QC Failed',
                                        ])
                                        ->default('Pending')
                                        ->required()
                                        ->columnSpan(2)
                                        ->label('QC Status')
                                        ->native(false),
                                ])
                                ->columns(9)
                                ->collapsible()
                                ->cloneable()
                                ->defaultItems(1)
                                ->createItemButtonLabel('Add New Product Line')
                                ->required(),
                        ]),
                ])->columns(["sm" => 2])->columnSpan(2),
                Forms\Components\Section::make("Time Stamps")
                    ->description("Details of when data was changed and also created")
                    ->schema([
                        Forms\Components\Placeholder::make("created_at")
                            ->content(fn(?ProductionReceipt $record): string => $record ? date_format($record->created_at, "M d, Y") : "-"),
                        Forms\Components\Placeholder::make("updated_at")
                            ->content(fn(?ProductionReceipt $record): string => $record ? date_format($record->updated_at, "M d, Y") : "-"),
                    ])
                    ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('receipt_number')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label("Receiver")
                    ->sortable()
                    ->placeholder('No User Assigned'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('Not Deleted'),
            ])
            ->emptyStateIcon('heroicon-o-arrow-down-tray')
            ->emptyStateDescription('Create product receipt and detail data.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create product receipt')
                    ->url(ProductionReceiptResource::getUrl('create'))
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
            'index' => Pages\ListProductionReceipts::route('/'),
            'create' => Pages\CreateProductionReceipt::route('/create'),
            'edit' => Pages\EditProductionReceipt::route('/{record}/edit'),
        ];
    }
}
