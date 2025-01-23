<?php

declare(strict_types=1);

namespace Stancl\Tenancy\Commands;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Concerns\DealsWithMigrations;
use Stancl\Tenancy\Concerns\ExtendsLaravelCommand;
use Stancl\Tenancy\Concerns\HasATenantsOption;
use Stancl\Tenancy\Events\DatabaseMigrated;
use Stancl\Tenancy\Events\MigratingDatabase;

class Migrate extends MigrateCommand
{
    use HasATenantsOption, DealsWithMigrations, ExtendsLaravelCommand;

    protected $description = 'Run migrations for tenant(s)';

    protected static function getTenantCommandName(): string
    {
        return 'tenants:migrate';
    }

    public function __construct(Migrator $migrator, Dispatcher $dispatcher)
    {
        
        parent::__construct($migrator, $dispatcher);

        $this->specifyParameters();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        tenancy()->runForMultiple($this->option('tenants'), function ($tenant) {
            foreach (config('tenancy.migration_parameters') as $parameter => $value) {
                if (! $this->input->hasParameterOption($parameter)) {
                    if($parameter == '--path')
                        $value[0] = $value[0] . '/' . $tenant->type;
                
                    $this->input->setOption(ltrim($parameter, '-'), $value);
                }
            }

            if (! $this->confirmToProceed()) {
                return;
            }

            $this->line("Tenant: {$tenant->getTenantKey()}");
            
            event(new MigratingDatabase($tenant));

            // Migrate
            parent::handle();

            event(new DatabaseMigrated($tenant));
        });
    }
}
