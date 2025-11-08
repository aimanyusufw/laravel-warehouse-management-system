<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;
use Parfaitementweb\FilamentCountryField\Tables\Columns\CountryColumn;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->placeholder('e.g., PT. Jaya Abadi')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('contact_person')
                        ->placeholder('e.g., John Doe')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->placeholder('e.g., contact@supplier.com')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    PhoneInput::make('phone_number')
                        ->placeholder('8123456789'),
                    Forms\Components\Textarea::make('address')
                        ->placeholder('Enter the full address here...')
                        ->columnSpanFull(),
                    Country::make('country')
                        ->default('ID')
                        ->searchable(),
                    Forms\Components\TextInput::make('tax_id')
                        ->placeholder('e.g., 99.888.777.6-555.444')
                        ->numeric()
                        ->step(20)
                        ->maxLength(255),
                    Forms\Components\Textarea::make('notes')
                        ->placeholder('Any additional notes about the supplier...')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(["sm" => 2])->columnSpan(2),
                Forms\Components\Section::make("Time Stamps")
                    ->description("details of when data was changed and also created")
                    ->schema([
                        Forms\Components\Placeholder::make("created_at")
                            ->content(fn(?Supplier $record): string => $record ? date_format($record->created_at, "M d, Y") : "-"),
                        Forms\Components\Placeholder::make("updated_at")
                            ->content(fn(?Supplier $record): string => $record ? date_format($record->updated_at, "M d, Y") : "-"),
                    ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->placeholder("N/A")
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person')
                    ->placeholder("N/A")
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->placeholder("No Email")
                    ->searchable(),
                PhoneColumn::make('phone_number')
                    ->placeholder("No Phone")
                    ->displayFormat(PhoneInputNumberType::INTERNATIONAL)
                    ->searchable(),
                CountryColumn::make('country')
                    ->placeholder("Unspecified")
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_id')
                    ->placeholder("No Tax ID")
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->emptyStateIcon('heroicon-o-truck')
            ->emptyStateDescription('Create supplier and detail data.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create units')
                    ->url(SupplierResource::getUrl('create'))
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
