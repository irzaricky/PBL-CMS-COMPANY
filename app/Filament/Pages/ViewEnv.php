<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use GeoSot\EnvEditor\Dto\BackupObj;
use GeoSot\EnvEditor\Dto\EntryObj;
use GeoSot\EnvEditor\Facades\EnvEditor;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\DeleteBackupAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\DownloadEnvFileAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\MakeBackupAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\RestoreBackupAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\ShowBackupContentAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\Backups\UploadBackupAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\CreateAction;
use GeoSot\FilamentEnvEditor\Pages\Actions\EditAction;
use GeoSot\FilamentEnvEditor\Pages\ViewEnv as BaseViewEnvEditor;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Url;
use SebastianBergmann\Diff\Differ;
use SebastianBergmann\Diff\Output\UnifiedDiffOutputBuilder;

class ViewEnv extends BaseViewEnvEditor
{
    // Search and filter properties
    #[Url]
    public string $searchTerm = '';

    #[Url]
    public string $categoryFilter = '';

    #[Url]
    public string $sortBy = 'key';
    #[Url]
    public string $sortDirection = 'asc';

    public bool $showEmpty = true;

    // Required for form functionality
    public function save(): void
    {
        // This method is required for Filament forms but we don't need to save anything
        // since we're just filtering and searching existing env data
    }

    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static ?string $navigationLabel = 'Environment Variables';
    protected static ?int $navigationSort = 5;

    public function getTitle(): string
    {
        return 'Environment Variables Management';
    }

    protected function getHeaderActions(): array
    {
        return [
            // Add Enhanced Backup Comparison action
            Action::make('compare_backup')
                ->label('Compare Backup')
                ->icon('heroicon-o-arrows-right-left')
                ->color('info')
                ->form([
                    Forms\Components\Select::make('backup_file')
                        ->label('Select Backup to Compare')
                        ->options(function () {
                            return EnvEditor::getAllBackUps()
                                ->pluck('name', 'name')
                                ->toArray();
                        })
                        ->required()
                        ->searchable()
                        ->placeholder('Choose a backup file to compare with current .env'),

                    Forms\Components\Radio::make('comparison_mode')
                        ->label('Comparison Mode')
                        ->options([
                            'side_by_side' => 'Side-by-Side View',
                            'unified_diff' => 'Unified Diff',
                            'variables_only' => 'Variables Only (Parsed)'
                        ])
                        ->default('side_by_side')
                        ->inline()
                        ->required(),
                ])->action(function (array $data) {
                    $this->showBackupComparison($data['backup_file'], $data['comparison_mode']);
                }),
        ];
    }    // Enhanced Backup Comparison Methods
    protected function showBackupComparison(string $backupFile, string $mode): void
    {
        try {
            $backupContent = $this->getBackupFileContent($backupFile);
            $currentContent = file_get_contents(app()->environmentFilePath());

            $comparison = $this->generateComparison($currentContent, $backupContent, $mode);

            Notification::make()
                ->title('Backup Comparison')
                ->body(new HtmlString($comparison))
                ->persistent()
                ->actions([
                    \Filament\Notifications\Actions\Action::make('close')
                        ->button()
                        ->close(),
                    \Filament\Notifications\Actions\Action::make('selective_restore')
                        ->button()
                        ->label('Selective Restore')
                        ->color('info')
                        ->action(function () use ($backupFile) {
                            $this->showSelectiveRestoreModal($backupFile);
                        }),
                    \Filament\Notifications\Actions\Action::make('restore_backup')
                        ->button()
                        ->color('warning')
                        ->action(function () use ($backupFile) {
                            // Simple JavaScript confirmation dialog
                            $this->js("
                                if (confirm('Are you sure you want to restore \'{$backupFile}\' and replace the current .env file?')) {
                                    window.location.href = '" . url()->current() . "?restore_backup={$backupFile}';
                                }
                            ");
                        })
                ])
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Comparison Failed')
                ->body('Unable to compare backup: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
    protected function showSelectiveRestoreModal(string $backupFile): void
    {
        try {
            $backupContent = $this->getBackupFileContent($backupFile);
            $currentContent = file_get_contents(app()->environmentFilePath());

            $currentVars = $this->parseEnvContent($currentContent);
            $backupVars = $this->parseEnvContent($backupContent);

            $differences = $this->getVariableDifferences($currentVars, $backupVars);

            $form = [
                Forms\Components\Section::make('Select Variables to Restore')
                    ->description("Choose which variables you want to restore from the backup '{$backupFile}'")
                    ->schema([
                        Forms\Components\CheckboxList::make('selected_variables')
                            ->label('Variables to Restore')
                            ->options($this->formatDifferencesForSelection($differences))
                            ->descriptions($this->formatDifferencesDescriptions($differences))
                            ->columns(1)
                            ->required(),
                    ])
            ];

            Action::make('selective_restore_modal')
                ->label('Selective Restore')
                ->form($form)
                ->action(function (array $data) use ($backupFile, $differences) {
                    $this->performSelectiveRestore($data['selected_variables'], $differences, $backupFile);
                })
                ->modalWidth(MaxWidth::FiveExtraLarge)
                ->dispatch('open-modal', ['id' => 'selective-restore']);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Selective Restore Failed')
                ->body('Unable to prepare selective restore: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getVariableDifferences(array $current, array $backup): array
    {
        $differences = [];
        $allKeys = array_unique(array_merge(array_keys($current), array_keys($backup)));

        foreach ($allKeys as $key) {
            $currentValue = $current[$key] ?? null;
            $backupValue = $backup[$key] ?? null;

            if ($currentValue !== $backupValue) {
                $differences[$key] = [
                    'key' => $key,
                    'current' => $currentValue,
                    'backup' => $backupValue,
                    'type' => $this->getDifferenceType($currentValue, $backupValue),
                    'category' => $this->getVariableCategory($key)
                ];
            }
        }

        return $differences;
    }

    protected function getDifferenceType(?string $current, ?string $backup): string
    {
        if ($current === null && $backup !== null) {
            return 'add'; // Variable exists in backup but not in current
        } elseif ($current !== null && $backup === null) {
            return 'remove'; // Variable exists in current but not in backup
        } else {
            return 'modify'; // Variable exists in both but with different values
        }
    }

    protected function formatDifferencesForSelection(array $differences): array
    {
        $options = [];

        foreach ($differences as $key => $diff) {
            // Skip 'remove' type differences for security reasons
            if ($diff['type'] === 'remove') {
                continue;
            }

            $label = $diff['key'];
            $badge = match ($diff['type']) {
                'add' => '➕ ADD',
                'modify' => '🔄 MODIFY',
            };

            $options[$key] = "{$badge} {$label}";
        }

        return $options;
    }

    protected function formatDifferencesDescriptions(array $differences): array
    {
        $descriptions = [];

        foreach ($differences as $key => $diff) {
            $current = $diff['current'] === null ? '(not set)' : "'{$diff['current']}'";
            $backup = $diff['backup'] === null ? '(not set)' : "'{$diff['backup']}'";

            $descriptions[$key] = "Current: {$current} → Backup: {$backup}";
        }

        return $descriptions;
    }

    protected function performSelectiveRestore(array $selectedKeys, array $differences, string $backupFile): void
    {
        try {
            $currentContent = file_get_contents(app()->environmentFilePath());
            $currentVars = $this->parseEnvContent($currentContent);

            $restoredCount = 0;

            foreach ($selectedKeys as $key) {
                if (isset($differences[$key])) {
                    $diff = $differences[$key];

                    switch ($diff['type']) {
                        case 'add':
                        case 'modify':
                            // Add or update the variable
                            $currentVars[$key] = $diff['backup'];
                            $restoredCount++;
                            break;

                        // Note: 'remove' case is disabled for security reasons
                        // Variables cannot be deleted through this interface
                    }
                }
            }

            // Rebuild .env content
            $newContent = $this->buildEnvContent($currentVars);
            // Write back to .env file
            file_put_contents(app()->environmentFilePath(), $newContent);

            $this->refresh();

            Notification::make()
                ->title('Selective Restore Completed')
                ->body("Successfully restored {$restoredCount} variables from backup '{$backupFile}'")
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Selective Restore Failed')
                ->body('Unable to perform selective restore: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function buildEnvContent(array $variables): string
    {
        $content = [];

        // Group variables by category for better organization
        $grouped = [];
        foreach ($variables as $key => $value) {
            $category = $this->getVariableCategory($key);
            $grouped[$category][] = ['key' => $key, 'value' => $value];
        }

        // Define category order and headers
        $categoryHeaders = [
            'app' => '# Application Configuration',
            'database' => '# Database Configuration',
            'cache' => '# Cache Configuration',
            'queue' => '# Queue Configuration',
            'mail' => '# Mail Configuration',
            'session' => '# Session Configuration',
            'security' => '# Security Configuration',
            'api' => '# API Configuration',
            'storage' => '# Storage Configuration',
            'logging' => '# Logging Configuration',
            'other' => '# Other Configuration',
        ];

        foreach ($categoryHeaders as $category => $header) {
            if (isset($grouped[$category])) {
                $content[] = '';
                $content[] = $header;

                foreach ($grouped[$category] as $var) {
                    $key = $var['key'];
                    $value = $var['value'];

                    // Quote values that contain spaces or special characters
                    if (str_contains($value, ' ') || str_contains($value, '#') || str_contains($value, '"')) {
                        $value = '"' . str_replace('"', '\\"', $value) . '"';
                    }

                    $content[] = "{$key}={$value}";
                }
            }
        }

        return implode("\n", $content) . "\n";
    }

    protected function generateComparison(string $current, string $backup, string $mode): string
    {
        switch ($mode) {
            case 'unified_diff':
                return $this->generateUnifiedDiff($current, $backup);

            case 'variables_only':
                return $this->generateVariablesComparison($current, $backup);

            case 'side_by_side':
            default:
                return $this->generateSideBySideComparison($current, $backup);
        }
    }

    protected function generateUnifiedDiff(string $current, string $backup): string
    {
        $builder = new UnifiedDiffOutputBuilder("--- Current .env\n+++ Backup\n");
        $differ = new Differ($builder);

        $diff = $differ->diff($current, $backup);

        return "<div class='font-mono text-sm bg-gray-900 text-white p-4 rounded-lg max-h-96 overflow-y-auto'>" .
            "<pre>" . htmlspecialchars($diff) . "</pre>" .
            "</div>";
    }

    protected function generateSideBySideComparison(string $current, string $backup): string
    {
        $currentLines = explode("\n", $current);
        $backupLines = explode("\n", $backup);

        $maxLines = max(count($currentLines), count($backupLines));

        $html = "<div class='grid grid-cols-2 gap-4 max-h-96 overflow-y-auto'>";

        // Headers
        $html .= "<div class='bg-blue-50 p-2 font-semibold text-blue-800 rounded'>Current .env</div>";
        $html .= "<div class='bg-green-50 p-2 font-semibold text-green-800 rounded'>Backup</div>";

        // Content comparison
        for ($i = 0; $i < $maxLines; $i++) {
            $currentLine = $currentLines[$i] ?? '';
            $backupLine = $backupLines[$i] ?? '';

            $currentClass = 'bg-white border p-2 text-sm font-mono';
            $backupClass = 'bg-white border p-2 text-sm font-mono';

            if ($currentLine !== $backupLine) {
                if (empty($currentLine)) {
                    $currentClass = 'bg-red-50 border-red-200 p-2 text-sm font-mono text-red-600';
                } elseif (empty($backupLine)) {
                    $backupClass = 'bg-red-50 border-red-200 p-2 text-sm font-mono text-red-600';
                } else {
                    $currentClass = 'bg-yellow-50 border-yellow-200 p-2 text-sm font-mono text-yellow-800';
                    $backupClass = 'bg-yellow-50 border-yellow-200 p-2 text-sm font-mono text-yellow-800';
                }
            }

            $html .= "<div class='{$currentClass}'>" . htmlspecialchars($currentLine ?: '(empty)') . "</div>";
            $html .= "<div class='{$backupClass}'>" . htmlspecialchars($backupLine ?: '(empty)') . "</div>";
        }

        $html .= "</div>";

        return $html;
    }

    protected function generateVariablesComparison(string $current, string $backup): string
    {
        $currentVars = $this->parseEnvContent($current);
        $backupVars = $this->parseEnvContent($backup);

        $allKeys = array_unique(array_merge(array_keys($currentVars), array_keys($backupVars)));
        sort($allKeys);

        $html = "<div class='space-y-2 max-h-96 overflow-y-auto'>";

        foreach ($allKeys as $key) {
            $currentValue = $currentVars[$key] ?? null;
            $backupValue = $backupVars[$key] ?? null;

            $status = 'unchanged';
            $statusColor = 'gray';
            $statusIcon = '○';

            if ($currentValue === null && $backupValue !== null) {
                $status = 'removed';
                $statusColor = 'red';
                $statusIcon = '✕';
            } elseif ($currentValue !== null && $backupValue === null) {
                $status = 'added';
                $statusColor = 'green';
                $statusIcon = '✓';
            } elseif ($currentValue !== $backupValue) {
                $status = 'modified';
                $statusColor = 'yellow';
                $statusIcon = '~';
            }

            $html .= "<div class='grid grid-cols-12 gap-2 items-center p-2 border rounded'>";
            $html .= "<div class='col-span-1 text-{$statusColor}-600 font-bold text-center'>{$statusIcon}</div>";
            $html .= "<div class='col-span-3 font-mono font-semibold'>" . htmlspecialchars($key) . "</div>";
            $html .= "<div class='col-span-4 font-mono text-sm bg-blue-50 p-1 rounded'>" .
                htmlspecialchars($currentValue ?? '(not set)') . "</div>";
            $html .= "<div class='col-span-4 font-mono text-sm bg-green-50 p-1 rounded'>" .
                htmlspecialchars($backupValue ?? '(not set)') . "</div>";
            $html .= "</div>";
        }

        $html .= "</div>";

        // Add legend
        $html .= "<div class='mt-4 text-xs text-gray-600'>";
        $html .= "<span class='text-green-600 mr-4'>✓ Added</span>";
        $html .= "<span class='text-red-600 mr-4'>✕ Removed</span>";
        $html .= "<span class='text-yellow-600 mr-4'>~ Modified</span>";
        $html .= "<span class='text-gray-600'>○ Unchanged</span>";
        $html .= "</div>";

        return $html;
    }

    protected function parseEnvContent(string $content): array
    {
        $variables = [];
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines and comments
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            // Parse KEY=VALUE format
            if (str_contains($line, '=')) {
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Remove quotes if present
                if (
                    (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                    (str_starts_with($value, "'") && str_ends_with($value, "'"))
                ) {
                    $value = substr($value, 1, -1);
                }

                $variables[$key] = $value;
            }
        }

        return $variables;
    }

    public function form(Form $form): Form
    {
        $tabs = Forms\Components\Tabs::make('Tabs')
            ->tabs([
                Forms\Components\Tabs\Tab::make(__('filament-env-editor::filament-env-editor.tabs.current-env.title'))
                    ->schema([
                        $this->getSearchAndFilterSection(),
                        $this->getEnvironmentStatsSection(),
                        ...$this->getFirstTab(),
                    ]),
                Forms\Components\Tabs\Tab::make(__('filament-env-editor::filament-env-editor.tabs.backups.title'))
                    ->schema($this->getSecondTab()),
            ]);

        return $form
            ->schema([$tabs]);
    }
    protected function getSearchAndFilterSection(): Component
    {
        return Forms\Components\Section::make('Search & Filter')
            ->schema([
                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\TextInput::make('searchTerm')
                            ->label('Search')
                            ->placeholder('Search by key or value...')
                            ->prefixIcon('heroicon-o-magnifying-glass')
                            ->extraInputAttributes(['wire:model.live.debounce.300ms' => 'searchTerm']),

                        Forms\Components\Select::make('categoryFilter')
                            ->label('Category')
                            ->placeholder('All Categories')
                            ->options([
                                'app' => 'Application',
                                'database' => 'Database',
                                'mail' => 'Mail',
                                'cache' => 'Cache',
                                'queue' => 'Queue',
                                'session' => 'Session',
                                'security' => 'Security',
                                'api' => 'API',
                                'storage' => 'Storage',
                                'logging' => 'Logging',
                                'other' => 'Other',
                            ])
                            ->extraInputAttributes(['wire:model.live' => 'categoryFilter']),

                        Forms\Components\Select::make('sortBy')
                            ->label('Sort By')
                            ->options([
                                'key' => 'Key',
                                'value' => 'Value',
                                'category' => 'Category',
                            ])
                            ->default('key')
                            ->extraInputAttributes(['wire:model.live' => 'sortBy']),

                        Forms\Components\Select::make('sortDirection')
                            ->label('Sort Direction')
                            ->options([
                                'asc' => 'Ascending',
                                'desc' => 'Descending',
                            ])
                            ->default('asc')
                            ->extraInputAttributes(['wire:model.live' => 'sortDirection']),
                    ]),

                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Toggle::make('showEmpty')
                            ->label('Show Empty Variables')
                            ->default(true)
                            ->afterStateUpdated(fn() => $this->resetPage()),

                        Forms\Components\Placeholder::make('filteredCount')
                            ->label('Filtered Results')
                            ->content(fn() => $this->getFilteredCount() . ' variables'),

                        Forms\Components\Placeholder::make('quickActions')
                            ->label('Quick Filters')
                            ->content(new HtmlString('
                                <div class="flex gap-2 flex-wrap">
                                    <button wire:click="filterByCategory(\'app\')" class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded hover:bg-blue-200">App</button>
                                    <button wire:click="filterByCategory(\'database\')" class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded hover:bg-green-200">Database</button>
                                    <button wire:click="filterByCategory(\'mail\')" class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded hover:bg-purple-200">Mail</button>
                                    <button wire:click="filterByCategory(\'security\')" class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded hover:bg-red-200">Security</button>
                                </div>
                            ')),
                    ]),
            ])
            ->collapsible()
            ->collapsed(false);
    }

    protected function getEnvironmentStatsSection(): Component
    {
        $stats = $this->getEnvironmentStats();
        return Forms\Components\Section::make('Environment Statistics')
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Placeholder::make('total')
                            ->label('Total Variables')
                            ->content(new HtmlString("<span class='text-2xl font-bold text-primary-600'>{$stats['total']}</span>")),
                        Forms\Components\Placeholder::make('empty')
                            ->label('Empty Variables')
                            ->content(new HtmlString("<span class='text-2xl font-bold text-danger-600'>{$stats['empty']}</span>")),
                        Forms\Components\Placeholder::make('categories')
                            ->label('Categories')
                            ->content(new HtmlString("<span class='text-2xl font-bold text-success-600'>{$stats['categories']}</span>")),
                    ])
            ])
            ->collapsible()
            ->collapsed(true);
    }    /**
         * @return list<Component>
         */
    protected function getFirstTab(): array
    {
        $envData = $this->getFilteredAndSortedEnvData();

        $header = Forms\Components\Group::make([
            Forms\Components\Actions::make([
                $this->createCustomCreateAction(),
            ])->alignEnd(),
        ]);

        return [
            $header,
            Forms\Components\Section::make('Environment Variables')
                ->schema([
                    Forms\Components\Tabs::make('env_tabs')
                        ->tabs([
                            Forms\Components\Tabs\Tab::make('by_category')
                                ->label('By Category')
                                ->schema($this->getEnvironmentVariablesByCategory($envData)),

                            Forms\Components\Tabs\Tab::make('all_variables')
                                ->label('All Variables')
                                ->schema($this->getAllEnvironmentVariables($envData)),
                        ])
                ]),
        ];
    }

    /**
     * @return list<Component>
     */
    protected function getSecondTab(): array
    {
        $data = EnvEditor::getAllBackUps()
            ->map(function (BackupObj $obj) {
                return Forms\Components\Group::make([
                    Forms\Components\Actions::make([
                        DeleteBackupAction::make("delete_{$obj->name}")->setEntry($obj),
                        DownloadEnvFileAction::make("download_{$obj->name}")->setEntry($obj->name)->hiddenLabel()->size(ActionSize::Small),
                        RestoreBackupAction::make("restore_{$obj->name}")->setEntry($obj->name),
                        ShowBackupContentAction::make("show_raw_content_{$obj->name}")->setEntry($obj),
                    ])->alignEnd(),
                    Forms\Components\Placeholder::make('name')
                        ->label('')
                        ->content(new HtmlString("<strong>{$obj->name}</strong>"))
                        ->columnSpan(2),
                    Forms\Components\Placeholder::make('created_at')
                        ->label('')
                        ->content($obj->createdAt->format('Y-m-d H:i:s'))
                        ->columnSpan(2),
                ])->columns(5);
            })->all();

        $header = Forms\Components\Group::make([
            Forms\Components\Actions::make([
                DownloadEnvFileAction::make('download_current')->tooltip('Download current .env file')->outlined(false),
                UploadBackupAction::make('upload'),
                MakeBackupAction::make('backup'),
            ])->alignEnd(),
        ]);

        return [$header, ...$data];
    }

    protected function getFilteredAndSortedEnvData(): Collection
    {
        $envData = EnvEditor::getEnvFileContent()
            ->filter(fn(EntryObj $obj) => !$obj->isSeparator())
            ->filter(fn(EntryObj $obj) => $this->matchesSearchAndFilter($obj));

        // Apply sorting
        return $envData->sortBy(function (EntryObj $obj) {
            return match ($this->sortBy) {
                'key' => $obj->key,
                'value' => $obj->getValue() ?? '',
                'category' => $this->getVariableCategory($obj->key),
                default => $obj->key,
            };
        }, SORT_REGULAR, $this->sortDirection === 'desc');
    }
    protected function getEnvironmentVariablesByCategory(Collection $envData): array
    {
        $groupedData = $envData->groupBy(fn(EntryObj $obj) => $this->getVariableCategory($obj->key));
        $sections = [];

        foreach ($groupedData as $category => $variables) {
            $categoryColor = $this->getCategoryColor($category);
            $count = $variables->count();

            $categoryIcon = $this->getCategoryIcon($category);

            $sections[] = Forms\Components\Section::make(
                new HtmlString("
                    <div class='flex items-center gap-2'>
                        <span class='text-lg'>{$categoryIcon}</span>
                        <span class='font-semibold'>" . ucfirst($category) . "</span>
                        <span class='inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{$categoryColor}-100 text-{$categoryColor}-800'>
                            {$count} variables
                        </span>
                    </div>
                ")
            )
                ->description("Environment variables in the {$category} category")
                ->schema($this->createVariableFields($variables))
                ->collapsible()
                ->collapsed($category === 'other');
        }

        return $sections;
    }

    protected function getAllEnvironmentVariables(Collection $envData): array
    {
        return $this->createVariableFields($envData);
    }

    protected function createVariableFields(Collection $variables): array
    {
        return $variables
            ->reject(fn(EntryObj $obj) => $this->shouldHideEnvVariable($obj->key))
            ->map(function (EntryObj $obj) {
                $color = $this->getVariableColor($obj->key);
                $displayValue = $obj->getValue();

                return Forms\Components\Group::make([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\Placeholder::make("key_{$obj->key}")
                                ->label('Key')
                                ->content(new HtmlString("
                                    <div class='flex items-center gap-2'>
                                        <span class='inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{$color}-100 text-{$color}-800'>
                                            {$this->getVariableCategory($obj->key)}
                                        </span>
                                        <code class='font-mono text-sm'>{$obj->key}</code>
                                        " . ($this->isSensitiveKey($obj->key) ? '<span class="text-warning-500 text-xs">🔒 Sensitive</span>' : '') . "
                                    </div>
                                ")),

                            Forms\Components\Placeholder::make("value_{$obj->key}")
                                ->label('Value')
                                ->content(new HtmlString("
                                    <div class='font-mono text-sm p-2 bg-gray-50 rounded border'>
                                        " . (empty($obj->getValue()) ? '<em class="text-gray-400">Empty</em>' : e($displayValue)) . "
                                    </div>
                                ")),

                            Forms\Components\Actions::make([
                                $this->createCustomEditAction($obj),
                            ])->alignEnd(),
                        ]),
                ])->columnSpanFull();
            })
            ->toArray();
    }

    // Helper Methods
    private function shouldHideEnvVariable(string $key): bool
    {
        return in_array($key, \GeoSot\FilamentEnvEditor\FilamentEnvEditorPlugin::get()->getHiddenKeys());
    }

    private function matchesSearchAndFilter(EntryObj $obj): bool
    {
        // Search filter
        $searchMatch = empty($this->searchTerm) ||
            str_contains(strtolower($obj->key), strtolower($this->searchTerm)) ||
            str_contains(strtolower($obj->getValue() ?? ''), strtolower($this->searchTerm));

        // Category filter
        $categoryMatch = empty($this->categoryFilter) ||
            $this->getVariableCategory($obj->key) === $this->categoryFilter;

        // Empty variables filter
        $emptyMatch = $this->showEmpty || !empty($obj->getValue());

        return $searchMatch && $categoryMatch && $emptyMatch;
    }
    private function getVariableCategory(string $key): string
    {
        $key = strtoupper($key);

        return match (true) {
            // Security keys should be checked first before APP_ prefix
            $key === 'APP_KEY' || str_starts_with($key, 'JWT_') || str_starts_with($key, 'ENCRYPTION_') => 'security',
            str_starts_with($key, 'APP_') => 'app',
            str_starts_with($key, 'DB_') || str_starts_with($key, 'DATABASE_') => 'database',
            str_starts_with($key, 'MAIL_') => 'mail',
            str_starts_with($key, 'CACHE_') || str_starts_with($key, 'REDIS_') => 'cache',
            str_starts_with($key, 'QUEUE_') => 'queue',
            str_starts_with($key, 'SESSION_') => 'session',
            str_starts_with($key, 'API_') || str_starts_with($key, 'STRIPE_') || str_starts_with($key, 'PAYPAL_') || str_starts_with($key, 'GOOGLE_') || str_starts_with($key, 'FACEBOOK_') => 'api',
            str_starts_with($key, 'AWS_') || str_starts_with($key, 'FILESYSTEM_') || str_starts_with($key, 'STORAGE_') => 'storage',
            str_starts_with($key, 'LOG_') => 'logging',
            default => 'other',
        };
    }

    private function getVariableColor(string $key): string
    {
        return match ($this->getVariableCategory($key)) {
            'app' => 'blue',
            'database' => 'green',
            'mail' => 'purple',
            'cache' => 'orange',
            'queue' => 'yellow',
            'session' => 'pink',
            'security' => 'red',
            'api' => 'indigo',
            'storage' => 'cyan',
            'logging' => 'gray',
            default => 'slate',
        };
    }

    private function getCategoryColor(string $category): string
    {
        return match ($category) {
            'app' => 'primary',
            'database' => 'success',
            'mail' => 'info',
            'cache' => 'warning',
            'queue' => 'danger',
            'session' => 'gray',
            'security' => 'danger',
            'api' => 'info',
            'storage' => 'success',
            'logging' => 'gray',
            default => 'gray',
        };
    }

    private function getCategoryIcon(string $category): string
    {
        return match ($category) {
            'app' => '⚙️',
            'database' => '🗄️',
            'mail' => '📧',
            'cache' => '🚀',
            'queue' => '📋',
            'session' => '🔐',
            'security' => '🛡️',
            'api' => '🔌',
            'storage' => '💾',
            'logging' => '📝',
            default => '📦',
        };
    }

    private function isSensitiveKey(string $key): bool
    {
        $sensitiveKeys = [
            'APP_KEY',
            'DB_PASSWORD',
            'JWT_SECRET',
            'ENCRYPTION_KEY',
            'MAIL_PASSWORD',
            'REDIS_PASSWORD',
            'STRIPE_SECRET',
            'PAYPAL_SECRET',
            'GOOGLE_CLIENT_SECRET',
            'FACEBOOK_CLIENT_SECRET',
            'AWS_SECRET_ACCESS_KEY',
            'PUSHER_APP_SECRET'
        ];

        return in_array(strtoupper($key), $sensitiveKeys) ||
            str_contains(strtolower($key), 'password') ||
            str_contains(strtolower($key), 'secret') ||
            str_contains(strtolower($key), 'key');
    }
    private function getEnvironmentStats(): array
    {
        $envData = EnvEditor::getEnvFileContent()
            ->filter(fn(EntryObj $obj) => !$obj->isSeparator());
        $total = $envData->count();
        $empty = $envData->filter(fn(EntryObj $obj) => empty($obj->getValue()))->count();
        $categories = $envData->groupBy(fn(EntryObj $obj) => $this->getVariableCategory($obj->key))->count();

        return compact('total', 'empty', 'categories');
    }

    private function getFilteredCount(): int
    {
        return $this->getFilteredAndSortedEnvData()->count();
    }    // Livewire Actions
    public function resetPage(): void
    {
        // Just refresh the component without saving
    }

    public function filterByCategory(string $category): void
    {
        $this->categoryFilter = $this->categoryFilter === $category ? '' : $category;
    }

    public function updatedSearchTerm(): void
    {
        // This method is called automatically when searchTerm changes
    }

    public function updatedCategoryFilter(): void
    {
        // This method is called automatically when categoryFilter changes  
    }

    public function updatedSortBy(): void
    {
        // This method is called automatically when sortBy changes
    }

    public function updatedSortDirection(): void
    {
        // This method is called automatically when sortDirection changes
    }
    public function updatedShowEmpty(): void
    {
        // This method is called automatically when showEmpty changes
    }

    /**
     * Get backup file content from storage
     */
    protected function getBackupFileContent(string $backupFile): string
    {
        $backupPath = storage_path('env-editor/' . $backupFile);

        if (!file_exists($backupPath)) {
            throw new \Exception("Backup file not found: {$backupFile}");
        }

        return file_get_contents($backupPath);
    }

    /**
     * Validate environment variable key format
     */
    protected function validateEnvKey(string $key): bool
    {
        // Environment variable keys should:
        // - Start with a letter or underscore
        // - Only contain uppercase letters, numbers, and underscores
        // - Not be empty
        return !empty($key) && preg_match('/^[A-Z_][A-Z0-9_]*$/', $key);
    }

    /**
     * Get validation error message for environment key
     */
    protected function getEnvKeyValidationMessage(): string
    {
        return 'Environment variable keys must start with a letter or underscore, contain only uppercase letters, numbers, and underscores.';
    }

    /**
     * Check if environment variable key already exists
     */
    protected function keyExists(string $key, ?string $excludeKey = null): bool
    {
        $envData = EnvEditor::getEnvFileContent();

        foreach ($envData as $entry) {
            if ($entry->key === $key && $key !== $excludeKey) {
                return true;
            }
        }

        return false;
    }    /**
         * Create custom CreateAction with validation
         */
    protected function createCustomCreateAction(): CreateAction
    {
        return CreateAction::make('Add')
            ->form([
                Forms\Components\TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->rules([
                        function () {
                            return function (string $attribute, $value, \Closure $fail) {
                                if (!$this->validateEnvKey($value)) {
                                    $fail($this->getEnvKeyValidationMessage());
                                }

                                if ($this->keyExists($value)) {
                                    $fail('This environment variable key already exists.');
                                }
                            };
                        },
                    ])
                    ->validationMessages([
                        'required' => 'Environment variable key is required.',
                    ])
                    ->placeholder('e.g., MY_NEW_VARIABLE'),

                Forms\Components\TextInput::make('value')
                    ->label('Value')
                    ->placeholder('Variable value'),
            ])
            ->action(function (array $data) {
                try {
                    $result = EnvEditor::addKey($data['key'], $data['value'] ?? '');

                    if ($result) {
                        Notification::make()
                            ->title('Success')
                            ->body("Environment variable '{$data['key']}' has been added successfully.")
                            ->success()
                            ->send();

                        // Refresh the page data
                        $this->dispatch('$refresh');
                    } else {
                        throw new \Exception('Failed to add environment variable');
                    }
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error')
                        ->body('Failed to add environment variable: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            })
            ->modalWidth(MaxWidth::Large);
    }    /**
         * Create custom EditAction with validation
         */
    protected function createCustomEditAction(EntryObj $obj): EditAction
    {
        return EditAction::make("edit_{$obj->key}")
            ->setEntry($obj)
            ->size(ActionSize::Small)
            ->form([
                Forms\Components\TextInput::make('key')
                    ->label('Key')
                    ->default($obj->key)
                    ->disabled()
                    ->helperText('Environment variable keys cannot be modified'),

                Forms\Components\TextInput::make('value')
                    ->label('Value')
                    ->default($obj->getValue()),
            ])
            ->action(function (array $data) use ($obj) {
                try {
                    // Only allow editing the value, not the key
                    $result = EnvEditor::editKey($obj->key, $data['value'] ?? '');

                    if ($result) {
                        Notification::make()
                            ->title('Success')
                            ->body("Environment variable '{$obj->key}' has been updated successfully.")
                            ->success()
                            ->send();

                        // Refresh the page data
                        $this->dispatch('$refresh');
                    } else {
                        throw new \Exception('Failed to update environment variable');
                    }
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error')
                        ->body('Failed to update environment variable: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            })
            ->modalWidth(MaxWidth::Large);
    }
}