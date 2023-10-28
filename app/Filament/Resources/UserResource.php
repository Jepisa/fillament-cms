<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Name')),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(User::class, 'email', $form->getRecord())
                    ->maxLength(255)
                    ->placeholder(__('Email')),
                TextInput::make('password')
                    ->password()
                    ->autocomplete('new-password')
                    ->confirmed()
                    ->required()
                    ->minLength(8)
                    ->maxLength(255)
                    ->placeholder(__('Password'))
                    ->hiddenOn('edit'),
                TextInput::make('password_confirmation')
                    ->password()
                    ->autocomplete('new-password')
                    ->required()
                    ->minLength(8)
                    ->maxLength(255)
                    ->placeholder(__('Confirm Password'))
                    ->hiddenOn('edit'),
                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email'),
                TextColumn::make('email_verified_at'),
                TextColumn::make('roles.name'),
                TextColumn::make('created_at')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\Filter::make('verified')
                    ->query(function (Builder $query) {
                       $query->whereNotNull('email_verified_at');
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn (User $user) => $user->hasRole('Admin')),
                Tables\Actions\Action::make('Verify')
                    ->icon('heroicon-o-check-circle')
                    ->action(fn (User $user) => $user->markEmailAsVerified())
                    ->hidden(fn (User $user) => $user->hasVerifiedEmail()),
                Tables\Actions\Action::make('Unverify')
                    ->icon('heroicon-o-x-circle')
                    ->action(function(User $user) {
                        $user->email_verified_at = null;
                        $user->save();
                    })
                    ->hidden(fn (User $user) => $user->hasRole('Admin') or !$user->hasVerifiedEmail()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
                ]),
                // BulkAction::make('export-jobs')
                //     ->label('Background Export')
                //     ->icon('heroicon-o-cog')
                //     ->action(function (Collection $records) {
                //         UsersCsvExportJob::dispatch($records, 'users.csv');
                //         Notification::make()
                //             ->title('Export is ready')
                //             ->body('Your export is ready. You can download it from the exports page.')
                //             ->success()
                //             ->seconds(5)
                //             ->icon('heroicon-o-inbox-in')
                //             ->send();
                //     })
            ])
            ->defaultSort('name', 'desc');
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
