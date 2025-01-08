<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\PusatInformasi;
use App\Filament\Resources\PesanResource\Pages;
use App\Filament\Resources\PesanResource\RelationManagers;
use App\Models\Pesan;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PesanResource extends Resource
{
    protected static ?string $model = Pesan::class;
    protected static ?string $cluster = PusatInformasi::class;
    protected static ?string $label = 'QNA';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $activeNavigationIcon = 'heroicon-s-chat-bubble-left-ellipsis';
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'warning' : 'info';
    }
    protected static ?string $navigationBadgeTooltip = 'Total QNA';
    protected static ?string $slug = 'pesan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Data Pengirim')
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Pengirim Pesan')
                                    ->placeholder('Nama Pengirim')
                                    ->required()
                                    ->maxLength(45),
                                TextInput::make('email')
                                    ->label('Email Pengirim Pesan')
                                    ->placeholder('Email Pengirim')
                                    ->email()
                                    ->required()
                                    ->maxLength(45),
                            ]),
                        Group::make()
                            ->schema([
                                Textarea::make('pesan')
                                    ->label('Pesan Pengirim')
                                    ->placeholder('Isi Pesan')
                                    ->rows(5)
                                    ->autosize()
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                    ])->columns(2)
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Pengirim')
                    ->description(fn(Pesan $record) => $record->email)
                    ->searchable(),
                TextColumn::make('pesan')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPesans::route('/'),
            'create' => Pages\CreatePesan::route('/create'),
            'view' => Pages\ViewPesan::route('/{record}'),
            'edit' => Pages\EditPesan::route('/{record}/edit'),
        ];
    }
}
