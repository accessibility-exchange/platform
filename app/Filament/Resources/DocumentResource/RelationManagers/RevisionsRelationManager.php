<?php

namespace App\Filament\Resources\DocumentResource\RelationManagers;

use App\Filament\Resources\RevisionResource;
use App\Models\Document;
use App\Models\Revision;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label(__('Revision date'))
                    ->format('Y-m-d')
                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, RelationManager $livewire) {
                        /** @var Document */
                        $document = $livewire->getOwnerRecord();

                        return $rule->where(fn (Builder $query) => $query->where('document_id', $document->id));
                    })
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
                    ->getUploadedFileNameForStorageUsing(function (?Revision $record, TemporaryUploadedFile $file, RelationManager $livewire, Get $get): string {
                        /** @var Document */
                        $document = $livewire->getOwnerRecord();

                        return RevisionResource::getFilename('en', $file->extension(), $document, $get('date'));
                    }),
                Forms\Components\FileUpload::make('file.fr')
                    ->label(__('File (French)'))
                    ->requiredWithout('file.en')
                    ->disk('public')
                    ->directory('documents')
                    ->getUploadedFileNameForStorageUsing(function (?Revision $record, TemporaryUploadedFile $file, RelationManager $livewire, Get $get): string {
                        /** @var Document */
                        $document = $livewire->getOwnerRecord();

                        return RevisionResource::getFilename('fr', $file->extension(), $document, $get('date'));
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('create_at')
            ->columns([
                Tables\Columns\TextColumn::make('date')->label(__('Revision date'))
                    ->date('Y-m-d'),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()->createAnother(false),
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
