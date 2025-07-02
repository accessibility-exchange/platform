<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToolResource\Pages;
use App\Filament\Resources\ToolResource\RelationManagers;
use App\Models\Tool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ToolResource extends Resource
{
    protected static ?string $model = Tool::class;

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Pages, resources and training';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Tool title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Tool title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Forms\Components\MarkdownEditor::make('description.en')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Description').' ('.get_language_exonym('en').')'),
                Forms\Components\MarkdownEditor::make('description.fr')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Description').' ('.get_language_exonym('fr').')'),
                Forms\Components\Section::make('Page Content')
                    ->schema([
                        Forms\Components\MarkdownEditor::make('content.en')
                            ->disableToolbarButtons(['attachFiles'])
                            ->label(__('Content').' ('.get_language_exonym('en').')')
                            ->columnSpan(2),
                        Forms\Components\MarkdownEditor::make('content.fr')
                            ->disableToolbarButtons(['attachFiles'])
                            ->label(__('Content').' ('.get_language_exonym('fr').')')
                            ->columnSpan(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('documents_count')
                    ->label(__('Documents'))
                    ->counts('documents'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date added'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Date modified'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
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
            RelationManagers\DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTools::route('/'),
            'create' => Pages\CreateTool::route('/create'),
            'edit' => Pages\EditTool::route('/{record}/edit'),
        ];
    }
}
