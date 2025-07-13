<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\VendorApplication;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;
use App\Filament\Resources\VendorApplicationResource\Pages;
use App\Filament\Resources\VendorApplicationResource\RelationManagers;

class VendorApplicationResource extends Resource
{
    protected static ?string $model = VendorApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'email', fn ($query) => $query->where('user_type', 2))
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('kitchen_name')
                    ->unique(ignoreRecord: true)
                    ->required(),
                FileUpload::make('cover_photo')
                    ->required()
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
                Forms\Components\TextInput::make('chef_name')
                    ->required(),

                Forms\Components\Select::make('profession_id')
                    ->relationship('profession', 'name')
                    ->required(),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),

                Fieldset::make('Location Details')
                    ->schema([
                        Geocomplete::make('address')
                        ->isLocation()
                        ->geocodeOnLoad()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if (!empty($state)) { // Ensure $state is not null
                                $set('latitude', $state['lat'] ?? null);
                                $set('longitude', $state['lng'] ?? null);
                                $set('location', $state['formatted_address'] ?? null);
                            }
                        }),

                    TextInput::make('latitude')
                        ->required()
                        ->readOnly()
                        ->reactive(),

                    TextInput::make('longitude')
                        ->required()
                        ->readOnly()
                        ->reactive(),

                    TextInput::make('location') // Ensure address is updated correctly
                        ->label('Location')
                        ->required(),
                                    ])
                    ->columnSpan('full'),
                    FileUpload::make('attachments')
                        ->multiple()
                        ->required()
                        ->label('Identity Verifiacation Photos')
                        ->columnSpanFull()
                        ->storeFileNamesIn('attachment_file_names'),

                    Repeater::make('links')
                        ->schema([
                            Forms\Components\Textarea::make('svg')->label('SVG icon')->required(),
                            TextInput::make('link')->required()
                        ])
                        ->columnSpanFull(),
                    Forms\Components\Section::make('Status')->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approval')
                            ->helperText('Whether or not the vendor is approved by admin.')
                            ->default(false),
                    ]),


                // Add other form fields here...
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kitchen_name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Approval')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('cover_photo'),
                Tables\Columns\TextColumn::make('chef_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('profession.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendorApplications::route('/'),
            'create' => Pages\CreateVendorApplication::route('/create'),
            'edit' => Pages\EditVendorApplication::route('/{record}/edit'),
        ];
    }
}
