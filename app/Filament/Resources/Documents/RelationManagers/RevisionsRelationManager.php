<?php

namespace App\Filament\Resources\Documents\RelationManagers;

use App\Filament\Resources\Revisions\RevisionResource;
use App\Models\Document;
use App\Models\Revision;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label(__('Revision date'))
                    ->format('Y-m-d')
                    ->unique(modifyRuleUsing: function ($rule, RelationManager $livewire) {
                        /** @var Document */
                        $document = $livewire->getOwnerRecord();

                        return $rule->where(fn (Builder $query) => $query->where('document_id', $document->id));
                    })
                    ->default(Carbon::now())
                    ->validationMessages([
                        'unique' => __('A revision with this date already exists.'),
                    ])
                    ->columnSpan(2),
                Section::make(__('Files'))
                    ->columnSpanFull()
                    ->description(__('Only Microsoft Excel, Microsoft PowerPoint, Microsoft Word, or Adobe PDF files are accepted.'))
                    ->schema([
                        FileUpload::make('file.en')
                            ->label(__('File (English)'))
                            ->requiredWithout('file.fr')
                            ->maxSize(102400)
                            ->directory('documents')
                            ->acceptedFileTypes(RevisionResource::getAcceptedFileTypes())
                            ->getUploadedFileNameForStorageUsing(function (?Revision $record, TemporaryUploadedFile $file, RelationManager $livewire, Get $get): string {
                                /** @var Document */
                                $document = $livewire->getOwnerRecord();

                                return RevisionResource::getFilename('en', $file->extension(), $document, $get('date'));
                            }),
                        FileUpload::make('file.fr')
                            ->label(__('File (French)'))
                            ->requiredWithout('file.en')
                            ->maxSize(102400)
                            ->directory('documents')
                            ->acceptedFileTypes(RevisionResource::getAcceptedFileTypes())
                            ->getUploadedFileNameForStorageUsing(function (?Revision $record, TemporaryUploadedFile $file, RelationManager $livewire, Get $get): string {
                                /** @var Document */
                                $document = $livewire->getOwnerRecord();

                                return RevisionResource::getFilename('fr', $file->extension(), $document, $get('date'));
                            }),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('create_at')
            ->columns([
                TextColumn::make('date')->label(__('Revision date'))
                    ->date('Y-m-d'),
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
            ->headerActions([
                CreateAction::make()->createAnother(false),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
