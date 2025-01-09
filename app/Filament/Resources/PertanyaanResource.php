<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\PusatInformasi;
use App\Filament\Resources\PertanyaanResource\Pages;
use App\Filament\Resources\PertanyaanResource\RelationManagers;
use App\Models\Pertanyaan;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PertanyaanResource extends Resource
{
    protected static ?string $model = Pertanyaan::class;
    protected static ?string $cluster = PusatInformasi::class;
    protected static ?string $label = 'FAQ';
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $activeNavigationIcon = 'heroicon-s-question-mark-circle';
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'warning' : 'info';
    }
    protected static ?string $navigationBadgeTooltip = 'Total FAQ';
    protected static ?string $slug = 'pertanyaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                Textarea::make('pertanyaan')
                                    ->label('Pertanyaan')
                                    ->placeholder('Masukkan Pertanyaan')
                                    ->minLength(10)
                                    ->required(),
                                Textarea::make('jawaban')
                                    ->label('Jawaban')
                                    ->placeholder('Masukkan Jawaban')
                                    ->minLength(10)
                                    ->required(),
                            ])->columnSpan(3),
                        Toggle::make('di_tampilkan')
                            ->label('Di Tampilkan ?')
                            ->inline(false)
                            ->required(),
                    ])->columns(4)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pertanyaan')
                    ->label('Pertanyaan & Jawaban')
                    ->description(fn(Pertanyaan $record): string => Str::limit($record->jawaban, 60))
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    }),
                ToggleColumn::make('di_tampilkan')
                    ->label('Di Tampilkan ?')
                    ->hidden(fn() => !Auth::user()->can('Ubah FAQ')),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
                    ->icon('heroicon-o-ellipsis-horizontal-circle')
                    ->color('info')
                    ->tooltip('Aksi')
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
            'index' => Pages\ListPertanyaans::route('/'),
            'create' => Pages\CreatePertanyaan::route('/create'),
            'view' => Pages\ViewPertanyaan::route('/{record}'),
            'edit' => Pages\EditPertanyaan::route('/{record}/edit'),
        ];
    }
}
