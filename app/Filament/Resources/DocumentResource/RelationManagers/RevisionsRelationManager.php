<?php

namespace App\Filament\Resources\DocumentResource\RelationManagers;

use App\Models\Document;
use App\Models\Revision;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('file.en')
                    ->label(__('File (English)'))
                    ->requiredWithout('file.fr')
                    ->disk('public')
                    ->directory('documents')
                    ->getUploadedFileNameForStorageUsing(
                        function (?Revision $record, TemporaryUploadedFile $file, RelationManager $livewire): string {
                            /** @var Document */
                            $document = $livewire->getOwnerRecord();

                            return $document->getRevisionFilename($record ? $record->created_at : Carbon::now(), 'en', $file->extension());
                        },
                    ),
                Forms\Components\FileUpload::make('file.fr')
                    ->label(__('File (French)'))
                    ->requiredWithout('file.en')
                    ->disk('public')
                    ->directory('documents')
                    ->getUploadedFileNameForStorageUsing(
                        function (?Revision $record, TemporaryUploadedFile $file, RelationManager $livewire): string {
                            /** @var Document */
                            $document = $livewire->getOwnerRecord();

                            return $document->getRevisionFilename($record ? $record->created_at : Carbon::now(), 'fr', $file->extension());
                        },
                    ),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('create_at')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label(__('Date'))
                    ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('has_english')->label(__('English'))
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : __('No')),
                Tables\Columns\TextColumn::make('has_french')->label(__('French'))
                    ->badge()
                    ->color(fn (string $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? __('Yes') : __('No')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
