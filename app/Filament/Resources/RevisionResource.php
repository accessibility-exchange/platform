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
                Forms\Components\FileUpload::make('file.en')
                    ->label(__('File (English)'))
                    ->requiredWithout('file.fr')
                    ->disk('public')
                    ->directory('documents')
                    ->getUploadedFileNameForStorageUsing(
                        function (?Revision $record, TemporaryUploadedFile $file, Get $get): string {
                            $document = Document::find($get('document_id'));

                            return $document->getRevisionFilename($record ? $record->created_at : Carbon::now(), 'en', $file->extension());
                        },
                    ),
                Forms\Components\FileUpload::make('file.fr')
                    ->label(__('File (French)'))
                    ->requiredWithout('file.en')
                    ->disk('public')
                    ->directory('documents')
                    ->getUploadedFileNameForStorageUsing(
                        function (?Revision $record, TemporaryUploadedFile $file, Get $get): string {
                            $document = Document::find($get('document_id'));

                            return $document->getRevisionFilename($record ? $record->created_at : Carbon::now(), 'fr', $file->extension());
                        },
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document.name'),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->date('Y-m-d')->sortable(),
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
}
