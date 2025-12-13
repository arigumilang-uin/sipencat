<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendDailyNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily notifications to Pemilik (inventory value, stock summary)';

    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Sending daily notifications to Pemilik...');

        // Send inventory value snapshot
        $this->notificationService->notifyPemilikInventoryValue();
        $this->info('✓ Inventory value notification sent');

        // Send low stock summary
        $this->notificationService->notifyPemilikLowStockSummary();
        $this->info('✓ Low stock summary sent');

        $this->info('Daily notifications completed!');

        return Command::SUCCESS;
    }
}
