<?php

namespace App\Console\Commands;

use App\Setting;
use App\User;
use App\VarianteTariffa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class BookingInstall extends Command
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
        $artisanCommands = array_keys(Artisan::all());

        $this->line('<pre>' . json_encode($this->arguments()) . "</pre>", null, "vvv");

        if ($key = config('app.key')) {
            $this->line("Esiste già una chiave per l'applicazione.", null, 'vvv');
        } else {
            if (in_array("key:generate", $artisanCommands)) {
                $key = Artisan::call("key:generate");
                $this->info("Chiave segreta generata.");
            } else $this->error("Non ho trovato il comando key:generate. Impossibile creare un app key.");
        }

        $this->info('Controllo se esiste un utente admin.', 'vvv');
        if (!User::whereRuolo('admin')->count()) {
            $this->info('Controllo se è impostata l\'email del webmaster in .env.', 'vvv');

            if (in_array('add:user', $artisanCommands) && $webmaster_email = config("booking.webmaster.email", false)) {
                $this->info($webmaster_email, 'vvv');

                // NON FUNZIONA!
                Artisan::call("add:user", [
                    "--admin" => true,
                    "email" => $webmaster_email,
                ], $this->getOutput());
            } else {
                $this->error("Nessuna email del webmaster impostata nel file .env. Non posso creare l'account.");
            }
        } else {
            $this->comment('Esiste già un admin. Non verrà ne verrà creato uno nuovo.', 'v');
        }

        $this->info('Controllo se esiste una tariffa.', 'vv');

        if (!VarianteTariffa::query()->count()) {
            $tariffa = new VarianteTariffa();
            $tariffa->slug = "intero";
            $tariffa->nome = "Intero";
            $tariffa->save() 
                ? $this->info('Tariffa creata.')
                : $this->error("Non è stato possibile creare la tariffa.");
        } else {
            $this->comment('Esiste già una tariffa base.', 'v');
        }

        $this->info('Provo ad installare Laravel Passport.', 'vvv');

        if (in_array("passport:install", $artisanCommands)) {
            if (!Setting::isFeatureInstalled("laravel/passport")) {
                Artisan::call("passport:install");
                Setting::setFeatureAsInstalled("laravel/passport");
            } else {
                $this->comment("Il comando passport:install è già stato eseguito.", 'vv');
            }
        }

        $this->info('Eseguo optimize.', 'vvv');

        if (in_array("optimize", $artisanCommands)) {
            Artisan::call("optimize");
        }


        $this->info("Fine del setup.");
    }
}
