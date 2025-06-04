<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $setting = Setting::first();

        if ($setting){
            $this->form->fill($setting->attributesToArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                ])
                ->schema([
                    Fieldset::make('Icon and Logo')
                      ->schema([
                        FileUpload::make('favicon')
                        ->label('Favicon PNG (32*32)px')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '1:1',
                        ])
                        ->required(),
                    FileUpload::make('logo')
                        ->label('Logo (max. 200*60)px')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->required(),
                    FileUpload::make('og_image')
                        ->label('Social share image')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios([
                            '1:1',
                        ])
                        ->required(),
                      ])
                ]),

                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                ])
                ->schema([
                    Fieldset::make('Website hero section')
                      ->schema([
                        TextInput::make('hero_title')
                            ->minLength(3)
                            ->maxLength(500)
                            ->required()
                            ->columnSpan(2),
                        Textarea::make('title_text')->columnSpan(2)->required(),
                        FileUpload::make('hero_background')
                            ->label('Hero background image')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('search_btn_title')
                            ->label('Search button title')
                            ->minLength(3)
                            ->maxLength(500)
                            ->required()
                            ->columnSpan(2),
                      ]),
                    Fieldset::make('Homestaurant Registration Section')
                       ->schema([
                        FileUpload::make('h_reg_image')
                            ->label('Homestaurant Reg. Photo')
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('h_reg_title')
                            ->label('Homestaurant Reg. title')
                            ->minLength(3)
                            ->maxLength(500)
                            ->required()
                            ->columnSpan(2),
                        Textarea::make('h_reg_paragraph')
                            ->label('Homestaurant Reg. paragraph')
                            ->minLength(3)
                            ->maxLength(500)
                            ->required()
                           ->columnSpanFull()
                           ->required(),
                        TextInput::make('h_reg_btn_text')
                            ->label('Homestaurant Reg. button title')
                            ->minLength(3)
                            ->maxLength(500)
                            ->required()
                            ->columnSpan(2),
                       ]),
                    Fieldset::make('Website Colors')
                          ->schema([
                            ColorPicker::make('primary_bg_color')
                             ->label('Primary background color')
                             ->nullable()
                             ->columnSpan(1),
                            ColorPicker::make('primary_text_color')
                             ->label('Primary text color')
                             ->nullable()
                             ->columnSpan(1),
                            ColorPicker::make('secondary_bg_color')
                             ->label('Secondary background color')
                             ->nullable()
                             ->columnSpan(1),
                            ColorPicker::make('secondary_text_color')
                             ->label('Secondary text color')
                             ->nullable()
                             ->columnSpan(1),
                            FileUpload::make('menu_card_bg_image')
                             ->label('Menu card colorful image')
                             ->image()
                             ->imageEditor()
                             ->imageEditorAspectRatios([
                                  '16:9',
                                  '4:3',
                                  '1:1',
                             ])
                             ->columnSpanFull()
                             ->nullable(),
                            ColorPicker::make('hover_bg_color')
                             ->label('Hover background color')
                             ->nullable()
                             ->columnSpan(1),
                            ColorPicker::make('hover_text_color')
                             ->label('Hover text color')
                             ->nullable()
                             ->columnSpan(1),
                          ]),
                    Fieldset::make('Search engine optimization')
                       ->schema([
                        Textarea::make('meta_description')
                           ->columnSpan(2)->required(),
                        Textarea::make('keywords')
                           ->columnSpan(2)->required(),
                       ]),
                    Textarea::make('copyright_text')->columnSpan(2)->required(),
                ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        // Retrieve the data from the form
        $data = $this->form->getState();

        // Attempt to find an existing record
        $existingSetting = Setting::first();

        if ($existingSetting) {
            // If an existing record was found, update it with the new data
            $existingSetting->update($data);
        } else {
            // If no existing record was found, create a new one
            Setting::create($data);
        }


        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
