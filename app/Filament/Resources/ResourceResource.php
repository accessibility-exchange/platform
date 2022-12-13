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
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ResourceResource extends Resource
{
    protected static ?string $model = ResourceModel::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-duplicate';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title.en')
                    ->label(__('Resource title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                Forms\Components\TextInput::make('title.fr')
                    ->label(__('Resource title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Forms\Components\Select::make('organization_id')
                    ->relationship('authorOrganization', 'name')
                    ->validationAttribute(__('author organization'))
                    ->columnSpan(2),
                Forms\Components\TextInput::make('author.en')
                    ->label(__('Author name').' ('.get_language_exonym('en').') ('.__('required without an author organization').')')
                    ->validationAttribute(__('English author name'))
                    ->requiredWithoutAll('organization_id,author.fr'),
                Forms\Components\TextInput::make('author.fr')
                    ->label(__('Author name').' ('.get_language_exonym('fr').') ('.__('required without an author organization').')')
                    ->validationAttribute(__('French author name'))
                    ->requiredWithoutAll('organization_id,author.en'),
                Forms\Components\TextInput::make('url.en')
                    ->label(__('Resource link').' ('.get_language_exonym('en').')')
                    ->activeUrl()
                    ->url()
                    ->requiredWithout('url.fr'),
                Forms\Components\TextInput::make('url.fr')
                    ->label(__('Resource link').' ('.get_language_exonym('fr').')')
                    ->activeUrl()
                    ->url()
                    ->requiredWithout('url.en'),
                Forms\Components\TextInput::make('url.asl')
                    ->label(__('Resource link').' ('.get_language_exonym('asl').')')
                    ->activeUrl()
                    ->url(),
                Forms\Components\TextInput::make('url.lsq')
                    ->label(__('Resource link').' ('.get_language_exonym('lsq').')')
                    ->activeUrl()
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
                Forms\Components\CheckboxList::make('phases')
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
                Tables\Columns\TextColumn::make('author')
                    ->formatStateUsing(fn (string $state, ResourceModel $record): string => $record->authorOrganization ? $record->authorOrganization->name : $state),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('contentType.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date added'))
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('content_type')->label(__('Content types'))->relationship('contentType', 'name'),
                SelectFilter::make('impacts')->multiple()->relationship('impacts', 'name'),
                SelectFilter::make('phases')
                    ->multiple()
                    ->query(fn (Builder $query, array $data): Builder => $query->whereJsonContains('phases', $data['values'])->orWhereNull('phases'))
                    ->options(self::getPhases()),
                SelectFilter::make('sectors')->multiple()->relationship('sectors', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()->url(fn (ResourceModel $record): string => localized_route('resources.show', $record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
