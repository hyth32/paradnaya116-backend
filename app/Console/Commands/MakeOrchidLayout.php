<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeOrchidLayout extends Command
{
    /**
     * Пример:
     * php artisan orchid:layout UserListLayout --type=table
     */
    protected $signature = 'orchid:layout {name : Class name} {--type=rows : Layout type (rows, table, chart, legend)}';

    protected $description = 'Create a new Orchid Layout class';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $type = $this->option('type');

        $namespace = "App\\Orchid\\Layouts";
        $path = app_path("Orchid/Layouts/{$name}.php");

        if ($this->files->exists($path)) {
            $this->error("Layout {$name} already exists!");
            return Command::FAILURE;
        }

        $stub = $this->getStub($type);
        $stub = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $name],
            $stub
        );

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $stub);

        $this->info("Layout {$name} created successfully.");
        return Command::SUCCESS;
    }

    protected function getStub(string $type): string
    {
        return match ($type) {
            'rows' => <<<PHP
<?php

namespace {{ namespace }};

use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;

class {{ class }} extends Rows
{
    protected function fields(): iterable
    {
        return [
            Input::make('example')->title('Example field'),
        ];
    }
}

PHP,
            'table' => <<<PHP
<?php

namespace {{ namespace }};

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class {{ class }} extends Table
{
    protected \$target = '';

    protected function columns(): iterable
    {
        return [
            TD::make('id'),
        ];
    }
}

PHP,
            'chart' => <<<PHP
<?php

namespace {{ namespace }};

use Orchid\Screen\Layouts\Chart;

class {{ class }} extends Chart
{
    protected \$type = 'line';
    protected \$target = 'charts';
    protected \$title = 'Example Chart';
    protected \$labels = ['One', 'Two', 'Three'];
}

PHP,
            'legend' => <<<PHP
<?php

namespace {{ namespace }};

use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class {{ class }} extends Legend
{
    protected \$target = 'user';

    protected function fields(): iterable
    {
        return [
            Sight::make('id'),
            Sight::make('name'),
        ];
    }
}

PHP,
            default => throw new \InvalidArgumentException("Unknown type: {$type}")
        };
    }
}
