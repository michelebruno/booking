<?php

namespace App\Console\Commands;

use App\Notifications\Welcome;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:user {email} {--p|password=?} {--A|admin} {--dont-confirm-input} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Registra un nuovo utente.';

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
        $this->info('Inizio la creazione di un nuovo utente', 'v');

        $email = $this->argument('email');

        if (!app()->environment(['local']) && !$this->option('dont-confirm-input')) {

            $confirmed_email = "";

            while ($confirmed_email !== $email) {
                if (!$confirmed_email) {
                    $this->error("L'email non corrisponde. Riprova.");
                }
                $confirmed_email = $this->question("Per sicurezza, conferma l'indirizzo email.");
            }
        }

        if (User::whereEmail($email)->count()) {
            $this->error("L'indirizzo email è già associato ad un altro account.");
            exit();
        }

        $user = new User();

        $user->email = $email;

        /**
         * Si può creare solo un utente admin. Clienti e fornitori hanno troppe informazioni aggiuntive che non si possono omettere.
         */
        if ($this->option("admin")) {
            $ruolo = User::RUOLO_ADMIN;
        } else {
            $ruolo = $this->choice("Con che ruolo vuoi creare l'utente?", [
                User::RUOLO_ADMIN,
                User::RUOLO_ACCOUNT,
            ], 0);
        }

        $user->ruolo = $ruolo;

        if (!$user->save()) {
            throw new \Exception("L'utente non è stato salvato correttamente.");
        } else $this->info("Utente salvato correttamente.");

        /**
         * La validiamo in automatico perché è stata inserita dal backend.
         */
        $user->markEmailAsVerified();

        $user->notify(new Welcome(true));

        $this->info("L'account è stato creato. Controlla la mail $user->email per verificare l'indirizzo.");
    }
}
