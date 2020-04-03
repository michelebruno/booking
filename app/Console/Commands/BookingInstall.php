<?php

namespace App\Console\Commands;

use App\Setting;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class BookingSetUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:install {--admin-email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Esegue le azioni di base.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if (!User::whereRuolo('admin')->count() && !Setting::isFeatureInstalled('admin_created')) {
            if (!$webmaster_email = env("BOOKING_WEBMASTER_EMAIL", false)) {
                $this->error("Nessuna email del webmaster impostata nel file .env. Non posso creare l'account.");
            } else {
                Artisan::call("add:user", [
                    "-a",
                    "email" => env("BOOKING_WEBMASTER_EMAIL")
                ]);
            }
        } else {
            $this->comment('Esiste già un admin. Non verrà ne verrà creato uno nuovo.', 'v');
        }
        $artisanCommands = array_keys(Artisan::all());

        if (in_array("passport:install", $artisanCommands)) {
            if (!Setting::isFeatureInstalled("laravel/passport")) {
                Artisan::call("passport:install");
            } else {
                $this->comment("Il comando passport:install è già stato eseguito.", 'vv');
            }
        }

        $this->info("Fine del setup.");
    }
}
