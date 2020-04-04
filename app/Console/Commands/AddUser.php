<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:user {email} {--p|password=?} {--A|admin} ';

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

        if (User::whereEmail($email)->count()) {
            $this->error("L'indirizzo email è già associato ad un altro account.");
            exit();
        }
        $user = new User();

        $user->email = $email;

        if ($this->option("admin")) {
            $ruolo = User::RUOLO_ADMIN;
        } else {
            $ruolo = $this->choice("Con che ruolo vuoi creare l'utente?", User::RUOLI);
        }
        $user->ruolo = $ruolo;

        $user->saveOrFail();

        $user->sendEmailVerificationNotification();

        $this->info("L'account è stato creato. Controlla la mail $user->email per verificare l'indirizzo.");
    }
}
