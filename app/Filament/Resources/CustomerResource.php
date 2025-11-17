<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
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

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->placeholder('PT Abadi Bakti Nusantara')
                        ->required()
                        ->helperText('Enter the full name of the client.')
                        ->columnSpanFull()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('contact_person')
                        ->required()
                        ->placeholder('Jane Smith')
                        ->helperText('Enter the primary contact person for this client.')
                        ->columnSpanFull()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->placeholder('example@email.com')
                        ->helperText('Enter a valid email address.')
                        ->email()
                        ->autocomplete(false)
                        ->columnSpanFull()
                        ->maxLength(255),
                    PhoneInput::make('phone')
                        ->placeholder('812-3456-7890')
                        ->helperText('Enter a valid phone number.'),
                    Country::make('country')
                        ->default('ID')
                        ->searchable()
                        ->helperText('Select the client’s country.'),
                    Forms\Components\Textarea::make('address')
                        ->rows(2)
                        ->placeholder('123 Main St, Jakarta')
                        ->helperText('Enter the client’s full address.')
                        ->autocomplete(false)
                        ->columnSpanFull()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('note')
                        ->rows(3)
                        ->placeholder('Additional notes about the client...')
                        ->helperText('Optional: add any extra information or remarks.')
                        ->autocomplete(false)
                        ->columnSpanFull()
                        ->maxLength(255),
                ])->columns(["sm" => 2])->columnSpan(2),
                Forms\Components\Section::make("Time Stamps")
                    ->description("Details of when data was changed and also created")
                    ->schema([
                        Forms\Components\Placeholder::make("created_at")
                            ->content(fn(?Customer $record): string => $record ? date_format($record->created_at, "M d, Y") : "-"),
                        Forms\Components\Placeholder::make("updated_at")
                            ->content(fn(?Customer $record): string => $record ? date_format($record->updated_at, "M d, Y") : "-"),
                    ])
                    ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Company Name')
                    ->searchable()
                    ->placeholder('No company name'),

                Tables\Columns\TextColumn::make('contact_person')
                    ->label('Contact Person')
                    ->searchable()
                    ->placeholder('No contact person'),

                PhoneColumn::make('phone')
                    ->displayFormat(PhoneInputNumberType::INTERNATIONAL)
                    ->placeholder('No phone number'),

                CountryColumn::make('country')
                    ->label('Country')
                    ->placeholder('No country selected'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('No email'),

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
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateDescription('Create customers and detail data.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create customers')
                    ->url(CustomerResource::getUrl('create'))
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
