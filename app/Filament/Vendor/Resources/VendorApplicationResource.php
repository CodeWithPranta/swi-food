<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\VendorApplicationResource\Pages;
use App\Filament\Vendor\Resources\VendorApplicationResource\RelationManagers;
use App\Models\VendorApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Cheesegrits\FilamentGoogleMaps\Fields\Geocomplete;
use Illuminate\Support\Facades\Auth;

class VendorApplicationResource extends Resource
{
    public static function getPluralLabel(): string
    {
        return 'Application';
    }

    public static function getLabel(): string
    {
        return 'Application';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id()); // Only show logged-in vendor's application
    }

    public static function canCreate(): bool
    {
        return false;
    }

    protected static ?string $model = VendorApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->default(fn () => auth()->id())
                    ->readOnly()
                    ->required(),
                Forms\Components\TextInput::make('kitchen_name')
                    ->unique(ignoreRecord: true)
                    ->readOnly(fn ($record) => $record?->exists)
                    ->required(),
                Forms\Components\FileUpload::make('cover_photo')
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

                Forms\Components\Fieldset::make('Location Details')
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

                    Forms\Components\TextInput::make('latitude')
                        ->required()
                        ->readOnly()
                        ->reactive(),

                    Forms\Components\TextInput::make('longitude')
                        ->required()
                        ->readOnly()
                        ->reactive(),

                    Forms\Components\TextInput::make('location') // Ensure address is updated correctly
                        ->label('Location')
                        ->required(),
                                    ])
                    ->columnSpan('full'),
                    Forms\Components\FileUpload::make('attachments')
                        ->multiple()
                        ->disabled()
                        ->required()
                        ->label('Identity Verifiacation Photos')
                        ->columnSpanFull()
                        ->storeFileNamesIn('attachment_file_names'),

                    Forms\Components\Repeater::make('links')
                        ->schema([
                            Forms\Components\Textarea::make('svg')->label('SVG icon')->required()
                                         ->helperText('Icons are available at https://flowbite.com/icons/'),
                            Forms\Components\TextInput::make('link')->required()
                        ])
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('opening_hours')
                        ->label('Opening Hours Per Week')
                        ->schema([
                            Forms\Components\Select::make('day')
                                ->options([
                                    'monday' => 'Monday',
                                    'tuesday' => 'Tuesday',
                                    'wednesday' => 'Wednesday',
                                    'thursday' => 'Thursday',
                                    'friday' => 'Friday',
                                    'saturday' => 'Saturday',
                                    'sunday' => 'Sunday',
                                ])
                                ->required()
                                ->distinct(), // ✅ ensures uniqueness inside the JSON, not DB column
                            Forms\Components\TimePicker::make('open')
                                ->label('Opens At')
                                ->withoutSeconds()
                                ->required(),
                            Forms\Components\TimePicker::make('close')
                                ->label('Closes At')
                                ->withoutSeconds()
                                ->required(),
                        ])
                        ->default([
                            ['day' => 'monday', 'open' => '09:00', 'close' => '21:00'],
                        ])
                        ->columnSpanFull()
                        ->helperText('You can specify opening and closing times per day of the week.')
                        ->addable(true)
                        ->reorderable(true)
                        ->deletable(true)
                        ->collapsible(),

                    Forms\Components\Section::make('Status')->schema([
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Approval')
                            ->disabled()
                            ->helperText('Whether or not the vendor is approved by admin.')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email'),
                Tables\Columns\TextColumn::make('kitchen_name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->boolean(),
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
            
            ])
            ->paginated(false);
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
            'edit' => Pages\EditVendorApplication::route('/{record}/edit'),
        ];
    }

}
