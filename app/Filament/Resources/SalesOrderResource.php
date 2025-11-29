<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesOrderResource\Pages;
use App\Filament\Resources\SalesOrderResource\RelationManagers;
use App\Models\SalesOrder;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesOrderResource extends Resource
{
    protected static ?string $model = SalesOrder::class;

    protected static ?string $navigationGroup = 'Outbound';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('so_number')
                        ->label('SO Number')
                        ->readOnly()
                        ->disabled()
                        ->default(function ($record) {
                            return $record
                                ? $record->so_number
                                : 'SO Number will be generated automatically by the system.';
                        }),
                    Forms\Components\Select::make('shipping_carrier_id')
                        ->label('Shipping Carrier')
                        ->placeholder('Select a shipping carrier')
                        ->helperText('Choose the carrier responsible for delivery.')
                        ->relationship('carrier', 'name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'Draft'        => 'Draft',
                            'Pending Pick' => 'Pending Pick',
                            'Picked'       => 'Picked',
                            'Packed'       => 'Packed',
                            'Shipped'      => 'Shipped',
                            'Closed'       => 'Closed',
                            'Canceled'     => 'Canceled',
                        ])
                        ->placeholder('Select order status')
                        ->helperText('Set the current workflow status for this order.')
                        ->default('Draft')
                        ->native(false)
                        ->required(),
                    Forms\Components\DatePicker::make('order_date')
                        ->label('Order Date')
                        ->placeholder('Select order creation date')
                        ->helperText('Date when this order was created.')
                        ->native(false)
                        ->suffixIcon("heroicon-o-calendar")
                        ->required(),
                    Forms\Components\TextInput::make('shipping_cost')
                        ->label('Shipping Cost')
                        ->placeholder('0.00')
                        ->helperText('The cost required to ship the order.')
                        ->mask(RawJs::make('$money($input)'))
                        ->prefix('IDR')
                        ->required()
                        ->dehydrateStateUsing(function ($state) {
                            if ($state === null || $state === '') {
                                return 0.0;
                            }
                            $clean = preg_replace('/[^0-9\.,]/', '', (string) $state);
                            $clean = str_replace(',', '.', $clean);
                            if (substr_count($clean, '.') > 1) {
                                $parts = explode('.', $clean);
                                $decimal = array_pop($parts);
                                $integer = implode('', $parts);
                                $clean = $integer . '.' . $decimal;
                            }
                            if ($clean === '' || $clean === '.') {
                                return 0.0;
                            }
                            return (float) $clean;
                        }),
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('Tracking Number')
                        ->placeholder('e.g., TRK123456789ID')
                        ->helperText('Tracking number provided by carrier.')
                        ->maxLength(255),
                ])->columns(["sm" => 1])->columnSpan(2),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Customer')
                            ->description('Select the customer associated with this order.')
                            ->schema([
                                Forms\Components\Select::make('customer_id')
                                    ->label('Customer')
                                    ->placeholder('Search customer...')
                                    ->helperText('Choose the customer for this sales order.')
                                    ->relationship('customer', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                            ]),
                        Forms\Components\Section::make('Time Stamps')
                            ->description('Details of when the record was created and last updated.')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Created At')
                                    ->content(
                                        fn(?SalesOrder $record): string =>
                                        $record ? $record->created_at->format('M d, Y H:i') : '-'
                                    ),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Updated At')
                                    ->content(
                                        fn(?SalesOrder $record): string =>
                                        $record ? $record->updated_at->format('M d, Y H:i') : '-'
                                    ),
                            ]),
                    ])
                    ->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('so_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('carrier.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Draft'         => 'gray',
                        'Pending Pick'  => 'warning',
                        'Picked'        => 'info',
                        'Packed'        => 'purple',
                        'Shipped'       => 'success',
                        'Closed'        => 'success',
                        'Canceled'      => 'danger',
                        default         => 'gray',
                    })
                    ->formatStateUsing(fn($state) => $state ?? 'Unknown'),
                Tables\Columns\TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_cost')
                    ->prefix("IDR ")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tracking_number')
                    ->Placeholder("Tracking Number is empty")
                    ->searchable(),
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
            ->emptyStateIcon('heroicon-o-shopping-bag')
            ->emptyStateDescription('Create sales order and detail data.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create sales order')
                    ->url(SalesOrderResource::getUrl('create'))
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesOrders::route('/'),
            'create' => Pages\CreateSalesOrder::route('/create'),
            'edit' => Pages\EditSalesOrder::route('/{record}/edit'),
        ];
    }
}
