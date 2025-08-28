<?php

namespace App\Filament\Resources\Revisions;

use App\Filament\Resources\Revisions\Pages\ManageRevisions;
use App\Models\Document;
use App\Models\Revision;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class RevisionResource extends Resource
{
    protected static ?string $model = Revision::class;

    protected static ?int $navigationSort = 9;

    protected static string|\UnitEnum|null $navigationGroup = 'Pages, resources and training';

    public static function getAcceptedFileTypes(): array
    {
        return [
            'application/msword',
            'application/pdf',
            'application/vnd.ms-excel',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('document_id')
                    ->relationship(name: 'document', titleAttribute: 'name')
                    ->columnSpan(2)
                    ->disabled(),
                DatePicker::make('date')
                    ->label(__('Revision date'))
                    ->format('Y-m-d')
                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, ?Revision $record) {
                        return $rule->where(fn (Builder $query) => $query->where('document_id', $record->document_id));
                    })
                    ->default(Carbon::now())
                    ->validationMessages([
                        'unique' => __('A revision with this date already exists.'),
                    ])
                    ->columnSpan(2),
                Section::make(__('Files'))
                    ->description(__('Only Microsoft Excel, Microsoft PowerPoint, Microsoft Word, or Adobe PDF files are accepted.'))
                    ->schema([
                        FileUpload::make('file.en')
                            ->label(__('File (English)'))
                            ->requiredWithout('file.fr')
                            ->disk('documents-s3')
                            ->maxSize(102400)
                            ->visibility('public')
                            ->directory('documents')
                            ->acceptedFileTypes(self::getAcceptedFileTypes())
                            ->getUploadedFileNameForStorageUsing(fn (?Revision $record, TemporaryUploadedFile $file, Get $get): string => self::getFilename('en', $file->extension(), Document::find($get('document_id')), $get('date'))),
                        FileUpload::make('file.fr')
                            ->label(__('File (French)'))
                            ->requiredWithout('file.en')
                            ->disk('documents-s3')
                            ->maxSize(102400)
                            ->visibility('public')
                            ->directory('documents')
                            ->acceptedFileTypes(self::getAcceptedFileTypes())
                            ->getUploadedFileNameForStorageUsing(fn (?Revision $record, TemporaryUploadedFile $file, Get $get): string => self::getFilename('fr', $file->extension(), Document::find($get('document_id')), $get('date'))),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('document.name'),
                TextColumn::make('date')->label(__('Revision date'))->date('Y-m-d')->sortable(),
                TextColumn::make('has_english')->label(__('English'))
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : __('No')),
                TextColumn::make('has_french')->label(__('French'))
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : __('No')),
            ])
            ->groups([
                Group::make('document.name'),
            ])
            ->recordActions([
                EditAction::make()->tooltip(fn (Revision $record): string => "Edit revision {$record->date} for {$record->document->name}"),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRevisions::route('/'),
        ];
    }

    public static function getFilename(string $language, string $extension, Document $document, string $date): string
    {
        $name = Str::slug($document->getTranslation('name', $language));

        return "$name-$date-$language.$extension";
    }
}
