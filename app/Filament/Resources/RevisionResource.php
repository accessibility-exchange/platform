<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RevisionResource\Pages;
use App\Models\Document;
use App\Models\Revision;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
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

    protected static ?string $navigationGroup = 'Pages, resources and training';

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('document_id')
                    ->relationship(name: 'document', titleAttribute: 'name')
                    ->columnSpan(2)
                    ->disabled(),
                Forms\Components\DatePicker::make('date')
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
                        Forms\Components\FileUpload::make('file.en')
                            ->label(__('File (English)'))
                            ->requiredWithout('file.fr')
                            ->disk('documents-s3')
                            ->maxSize(102400)
                            ->visibility('public')
                            ->directory('documents')
                            ->acceptedFileTypes(self::getAcceptedFileTypes())
                            ->getUploadedFileNameForStorageUsing(fn (?Revision $record, TemporaryUploadedFile $file, Get $get): string => self::getFilename('en', $file->extension(), Document::find($get('document_id')), $get('date'))),
                        Forms\Components\FileUpload::make('file.fr')
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
                Tables\Columns\TextColumn::make('document.name'),
                Tables\Columns\TextColumn::make('date')->label(__('Revision date'))->date('Y-m-d')->sortable(),
                Tables\Columns\TextColumn::make('has_english')->label(__('English'))
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : __('No')),
                Tables\Columns\TextColumn::make('has_french')->label(__('French'))
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->icon(fn (string $state): string => $state ? 'heroicon-o-check' : 'heroicon-o-x-mark')
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : __('No')),
            ])
            ->groups([
                Group::make('document.name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->tooltip(fn (Revision $record): string => "Edit revision {$record->date} for {$record->document->name}"),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRevisions::route('/'),
        ];
    }

    public static function getFilename(string $language, string $extension, Document $document, string $date): string
    {
        $name = Str::slug($document->getTranslation('name', $language));

        return "$name-$date-$language.$extension";
    }
}
