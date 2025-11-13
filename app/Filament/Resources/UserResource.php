<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'App Management';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->placeholder("Jhon Doe")
                        ->required()
                        ->helperText('Input must valid name')
                        ->columnSpanFull()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->unique(ignoreRecord: true)
                        ->placeholder("example@email.com")
                        ->helperText('Input must valid email')
                        ->email()
                        ->autocomplete(false)
                        ->columnSpanFull()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->placeholder("+1234567890")
                        ->helperText('Input must valid phone number')
                        ->tel()
                        ->autocomplete(false)
                        ->columnSpanFull()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('address')
                        ->rows(3)
                        ->placeholder("123 Main St")
                        ->helperText('Input must valid address')
                        ->autocomplete(false)
                        ->columnSpanFull()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->minLength(8)
                        ->revealable()
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                        ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)
                        ->dehydrated(fn($state) => filled($state))
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('confirm_password')
                        ->password()
                        ->minLength(8)
                        ->required(
                            fn($livewire) =>
                            $livewire instanceof Pages\CreateUser ||
                                ($livewire instanceof Pages\EditUser && filled($livewire->data['password']))
                        )
                        ->same('password')
                        ->revealable()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ])->columns(["sm" => 2])->columnSpan(2),
                Forms\Components\Grid::make()->schema([
                    Forms\Components\Section::make("Authority")
                        ->description("details about the authority")
                        ->schema([
                            Forms\Components\Select::make('roles')
                                ->relationship('roles', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable(),
                        ]),
                    Forms\Components\Section::make("Time Stamps")
                        ->description("details of when data was changed and also created")
                        ->schema([
                            Forms\Components\Placeholder::make("created_at")
                                ->content(fn(?User $record): string => $record ? date_format($record->created_at, "M d, Y") : "-"),
                            Forms\Components\Placeholder::make("updated_at")
                                ->content(fn(?User $record): string => $record ? date_format($record->updated_at, "M d, Y") : "-"),
                        ])
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('id', '!=', Auth::id()))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->placeholder("Email is empty")
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->placeholder("Phone is empty")
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('roles.name')
                    ->default("User has no role")
                    ->colors([
                        'danger' => 'User has no role'
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateDescription('Create user and detail data.')
            ->emptyStateActions([
                Action::make('create')
                    ->label('Create user')
                    ->url(UserResource::getUrl('create'))
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
