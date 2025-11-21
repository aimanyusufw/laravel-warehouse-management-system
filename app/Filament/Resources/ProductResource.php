<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Placeholder::make('qr_code_path')
                        ->content(function ($record): HtmlString {
                            if (!$record || $record->qr_code_path == "") {
                                return new HtmlString("
                                                <div class='p-4 w-32 h-32 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 text-gray-500 text-sm'>
                                                    <span>No data available</span>
                                                    </div>
                                            ");
                            }
                            return new HtmlString("
                                                <img 
                                                    src='" . asset('storage/' . $record->qr_code_path) . "' 
                                                    alt='QR Code Preview' 
                                                    class='h-42 w-42 object-contain rounded-lg border border-gray-200 shadow-sm'
                                                >
                                            ");
                        })
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('sku')
                        ->label('SKU')
                        ->unique(ignoreRecord: true)
                        ->placeholder('Enter product SKU')
                        ->helperText('Unique stock keeping unit used to identify electronic items in the warehouse.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name')
                        ->placeholder('Enter product name')
                        ->helperText('Official product name used in manufacturing and warehouse records.')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('unit')
                        ->placeholder('Pcs')
                        ->helperText('Measurement unit for the product. Common units include Pcs, Box, or Carton.')
                        ->required()
                        ->maxLength(255)
                        ->default('Pcs'),
                    Forms\Components\TextInput::make('min_stock')
                        ->placeholder('Set minimum stock level')
                        ->helperText('Minimum required stock level to avoid shortage in production or shipping.')
                        ->required()
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('retail_price')
                        ->placeholder('Enter retail price')
                        ->helperText('Retail price of the electronic product based on company pricing rules.')
                        ->required()
                        ->prefix("IDR")
                        ->numeric(),
                    Forms\Components\TextInput::make('weight')
                        ->placeholder('Enter product weight (kg)')
                        ->helperText('Actual product weight used for logistics, packing, and warehouse handling.')
                        ->required()
                        ->suffix("Kg")
                        ->numeric(),
                ])->columns(["sm" => 2])->columnSpan(2),
                Forms\Components\Section::make("Time Stamps")
                    ->description("Details of when data was changed and also created")
                    ->schema([
                        Forms\Components\Placeholder::make("created_at")
                            ->content(fn(?Product $record): string => $record ? date_format($record->created_at, "M d, Y") : "-"),
                        Forms\Components\Placeholder::make("updated_at")
                            ->content(fn(?Product $record): string => $record ? date_format($record->updated_at, "M d, Y") : "-"),
                    ])
                    ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('min_stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('retail_price')
                    ->numeric()
                    ->prefix("IDR ")
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->suffix(" Kg")
                    ->numeric()
                    ->sortable(),
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
            ->emptyStateIcon('heroicon-o-gift')
            ->emptyStateDescription('Create product and detail data.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create product carrier')
                    ->url(ProductResource::getUrl('create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->actions([
                Action::make('print_qr')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->url(fn($record) => route('product.print', $record))
                    ->openUrlInNewTab()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
