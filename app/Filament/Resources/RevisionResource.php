<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RevisionResource\Pages;
use App\Models\Document;
use App\Models\Revision;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class RevisionResource extends Resource
{
    protected static ?string $model = Revision::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 9;

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
                    ->unique()
                    ->default(Carbon::now())
                    ->validationMessages([
                        'unique' => __('A revision with this date already exists.'),
                    ])
                    ->columnSpan(2),
                Forms\Components\FileUpload::make('file.en')
                    ->label(__('File (English)'))
                    ->requiredWithout('file.fr')
                    ->disk('public')
                    ->directory('documents')
                    ->getUploadedFileNameForStorageUsing(fn (?Revision $record, TemporaryUploadedFile $file, Get $get): string => self::getFilename('en', $file->extension(), Document::find($get('document_id')), $record)),
                Forms\Components\FileUpload::make('file.fr')
                    ->label(__('File (French)'))
                    ->requiredWithout('file.en')
                    ->disk('public')
                    ->directory('documents')
                    ->getUploadedFileNameForStorageUsing(fn (?Revision $record, TemporaryUploadedFile $file, Get $get): string => self::getFilename('fr', $file->extension(), Document::find($get('document_id')), $record)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document.name'),
                Tables\Columns\TextColumn::make('date')->label('Date')->date('Y-m-d')->sortable(),
                Tables\Columns\TextColumn::make('has_english')->label(__('English'))
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : __('No')),
                Tables\Columns\TextColumn::make('has_french')->label(__('French'))
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : __('No')),
            ])
            ->groups([
                Group::make('document.name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getFilename(string $language, string $extension, Document $document, ?Revision $revision): string
    {
        $name = Str::slug($document->getTranslation('name', $language));
        $date = $revision ? $revision->date->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        return "$name-$date-$language.$extension";
    }
}
