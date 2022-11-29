<?php

namespace App\Filament\Resources;

use App\Enums\ConsultationPhase;
use App\Filament\Resources\ResourceResource\Pages;
use App\Models\Resource as ResourceModel;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ResourceResource extends Resource
{
    protected static ?string $model = ResourceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('author.en')
                    ->label(__('Author name').' ('.get_language_exonym('en').')')
                    ->requiredWithout('author.fr'),
                Forms\Components\TextInput::make('author.fr')
                    ->label(__('Author name').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('author.en'),
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Resource title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Resource title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Forms\Components\TextInput::make('url.en')
                    ->label(__('Resource link').' ('.get_language_exonym('en').')')
                    ->requiredWithout('url.fr'),
                Forms\Components\TextInput::make('url.fr')
                    ->label(__('Resource link').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('url.en'),
                Forms\Components\TextInput::make('url.asl')
                    ->label(__('Resource link').' ('.get_language_exonym('asl').')')
                    ->url(),
                Forms\Components\TextInput::make('url.lsq')
                    ->label(__('Resource link').' ('.get_language_exonym('lsq').')')
                    ->url(),
                Forms\Components\MarkdownEditor::make('summary.en')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Summary').' ('.get_language_exonym('en').')')
                    ->columnSpan(2),
                Forms\Components\MarkdownEditor::make('summary.fr')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Summary').' ('.get_language_exonym('fr').')')
                    ->columnSpan(2),
                Forms\Components\Select::make('content_type_id')
                    ->relationship('contentType', 'name')
                    ->columnSpan(2),
                Forms\Components\CheckboxList::make('phase')
                    ->label(__('Phases of consultation'))
                    ->options(self::getPhases())
                    ->columnSpan(2),
                Forms\Components\CheckboxList::make('topics')
                    ->label(__('Topics'))
                    ->relationship('topics', 'name')
                    ->columnSpan(2),
                Forms\Components\CheckboxList::make('sectors')
                    ->label(__('Sectors'))
                    ->relationship('sectors', 'name')
                    ->columnSpan(2),
                Forms\Components\CheckboxList::make('impacts')
                    ->label(__('Areas of impact'))
                    ->relationship('impacts', 'name')
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('author'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('contentType.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date added'))
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListResources::route('/'),
            'create' => Pages\CreateResource::route('/create'),
            'edit' => Pages\EditResource::route('/{record}/edit'),
        ];
    }

    public static function getPhases(): array
    {
        $phases = [];
        foreach (array_column(ConsultationPhase::cases(), 'value') as $key) {
            $phases[$key] = ConsultationPhase::labels()[$key];
        }

        return $phases;
    }
}
