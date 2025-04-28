<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Setting;

class CheckSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check customer care settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // First show all settings
        $settings = Setting::all();
        
        $this->info('All settings:');
        $this->table(
            ['ID', 'Key', 'Value', 'Type', 'Group', 'Description'],
            $settings->map(function ($setting) {
                return [
                    'id' => $setting->id,
                    'key' => $setting->key,
                    'value' => is_string($setting->value) ? $setting->value : json_encode($setting->value),
                    'type' => $setting->type,
                    'group' => $setting->group,
                    'description' => $setting->description
                ];
            })
        );
        
        // Specifically check customer_care setting
        $this->info("\nSpecifically checking customer_care setting:");
        $customerCare = Setting::where('key', 'customer_care')->first();
        
        if ($customerCare) {
            $this->info("Customer care setting found with ID: " . $customerCare->id);
            $this->info("Value: " . (is_string($customerCare->value) ? $customerCare->value : json_encode($customerCare->value)));
            $this->info("Type: " . $customerCare->type);
            $this->info("Group: " . $customerCare->group);
        } else {
            $this->error("No customer_care setting found in the database!");
        }
        
        return 0;
    }
}
