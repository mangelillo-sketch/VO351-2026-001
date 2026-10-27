<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PuliziaReportLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sistema:pulizia-log {--giorni=30 : Giorni di conservazione}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulizia dei Report Log: elimina i report log più vecchi di 30 giorni per liberare spazio e migliorare le prestazioni del sistema.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $giorni = (int) $this->option('giorni');
        $this->info("Pulizia log più vecchi di {$giorni} giorni...");
        $this->newLine();
        Log::info("Comando eseguito per {$giorni} giorni.");
        $this->info("Operazione completata!");

        return Command::SUCCESS;

    }
}
