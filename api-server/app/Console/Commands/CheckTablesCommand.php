<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTablesCommand extends Command
{
    protected $signature = 'db:check-tables';
    protected $description = 'Check if specific tables exist in the database';

    public function handle()
    {
        $this->info('Checking database tables...');
        
        // Check if terms table exists
        if (Schema::hasTable('terms')) {
            $this->info('✅ Terms table exists');
            
            // Get columns
            $columns = Schema::getColumnListing('terms');
            $this->info('Columns: ' . implode(', ', $columns));
            
            // Count records
            $count = DB::table('terms')->count();
            $this->info("Record count: {$count}");
        } else {
            $this->error('❌ Terms table does NOT exist');
        }
        
        // List all tables
        $this->info("\nAll tables in database:");
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $this->line("- {$tableName}");
        }
        
        return 0;
    }
} 