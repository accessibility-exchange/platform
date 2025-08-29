<?php

namespace App\Filament\Resources\Resources;

use App\Enums\ConsultationPhase;
use App\Filament\Resources\Resources\Pages\CreateResource;
use App\Filament\Resources\Resources\Pages\EditResource;
use App\Filament\Resources\Resources\Pages\ListResources;
use App\Models\Impact;
use App\Models\Resource as ResourceModel;
use App\Models\ResourceType;
use App\Models\Sector;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ResourceResource extends Resource
{
    protected static ?string $model = ResourceModel::class;

    protected static ?int $navigationSort = 8;

    protected static string|\UnitEnum|null $navigationGroup = 'Pages, resources and training';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title.en')
                    ->label(__('Resource title').' ('.get_language_exonym('en').')')
                    ->requiredWithout('title.fr'),
                TextInput::make('title.fr')
                    ->label(__('Resource title').' ('.get_language_exonym('fr').')')
                    ->requiredWithout('title.en'),
                Select::make('organization_id')
                    ->relationship('authorOrganization', 'name')
                    ->validationAttribute(__('author organization'))
                    ->columnSpan(2),
                TextInput::make('author.en')
                    ->label(__('Author name').' ('.get_language_exonym('en').') ('.__('required without an author organization').')')
                    ->validationAttribute(__('English author name'))
                    ->requiredWithoutAll('organization_id,author.fr'),
                TextInput::make('author.fr')
                    ->label(__('Author name').' ('.get_language_exonym('fr').') ('.__('required without an author organization').')')
                    ->validationAttribute(__('French author name'))
                    ->requiredWithoutAll('organization_id,author.en'),
                TextInput::make('url.en')
                    ->label(__('Resource link').' ('.get_language_exonym('en').')')
                    ->activeUrl()
                    ->url()
                    ->requiredWithout('url.fr'),
                TextInput::make('url.fr')
                    ->label(__('Resource link').' ('.get_language_exonym('fr').')')
                    ->activeUrl()
                    ->url()
                    ->requiredWithout('url.en'),
                TextInput::make('url.asl')
                    ->label(__('Resource link').' ('.get_language_exonym('asl').')')
                    ->activeUrl()
                    ->url(),
                TextInput::make('url.lsq')
                    ->label(__('Resource link').' ('.get_language_exonym('lsq').')')
                    ->activeUrl()
                    ->url(),
                MarkdownEditor::make('summary.en')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Summary').' ('.get_language_exonym('en').')')
                    ->columnSpan(2),
                MarkdownEditor::make('summary.fr')
                    ->toolbarButtons(['bold', 'italic', 'edit', 'preview'])
                    ->label(__('Summary').' ('.get_language_exonym('fr').')')
                    ->columnSpan(2),
                Select::make('content_type_id')
                    ->relationship('resourceType', 'name')
                    ->getOptionLabelFromRecordUsing(fn (mixed $record) => $record->name)
                    ->columnSpan(2),
                CheckboxList::make('phases')
                    ->label(__('Phases of consultation'))
                    ->options(self::getPhases())
                    ->columnSpan(2),
                CheckboxList::make('topics')
                    ->label(__('Topics'))
                    ->relationship('topics', 'name')
                    ->getOptionLabelFromRecordUsing(fn (mixed $record) => $record->name)
                    ->columnSpan(2),
                CheckboxList::make('sectors')
                    ->label(__('Sectors'))
                    ->relationship('sectors', 'name')
                    ->getOptionLabelFromRecordUsing(fn (mixed $record) => $record->name)
                    ->columnSpan(2),
                CheckboxList::make('impacts')
                    ->label(__('Areas of impact'))
                    ->relationship('impacts', 'name')
                    ->getOptionLabelFromRecordUsing(fn (mixed $record) => $record->name)
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('author')
                    ->formatStateUsing(fn (string $state, ResourceModel $record): string => $record->authorOrganization ? $record->authorOrganization->name : $state),
                TextColumn::make('title'),
                TextColumn::make('resourceType.name'),
                TextColumn::make('created_at')
                    ->label(__('Date added'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Date modified'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('content_type')->label(__('Resource types'))->relationship('resourceType', 'name')->getOptionLabelFromRecordUsing(fn (ResourceType $record) => $record->name),
                SelectFilter::make('impacts')->multiple()->relationship('impacts', 'name')->getOptionLabelFromRecordUsing(fn (Impact $record) => $record->name),
                SelectFilter::make('phases')
                    ->multiple()
                    ->query(fn (Builder $query, array $data): Builder => $query->whereJsonContains('phases', $data['values'])->orWhereNull('phases'))
                    ->options(self::getPhases()),
                SelectFilter::make('sectors')->multiple()->relationship('sectors', 'name')->getOptionLabelFromRecordUsing(fn (Sector $record) => $record->name),
            ])
            ->recordActions([
                EditAction::make()->tooltip(fn (ResourceModel $record): string => __('Edit :title', ['title' => $record->title])),
                ViewAction::make()->url(fn (ResourceModel $record): string => localized_route('resources.show', $record))->tooltip(fn (ResourceModel $record): string => __('View :title', ['title' => $record->title])),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->paginated([10, 25, 50, 'all']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListResources::route('/'),
            'create' => CreateResource::route('/create'),
            'edit' => EditResource::route('/{record}/edit'),
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
