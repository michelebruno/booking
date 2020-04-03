<?php

namespace Deployer;

use function GuzzleHttp\Promise\task;

require 'recipe/laravel.php';

set('ssh_type', 'native');
set('ssh_multiplexing', false);
// Project name
set('application', 'booking');



// Project repository
set('repository', 'git@github.com:michelebruno/booking.git');


// [Optional] Allocate tty for git clone. Default value is false.
//set('git_tty', true);

// Shared files/dirs between deploys 
// add('shared_files', []);
// add('shared_dirs', []);

// Writable dirs by web server 
// add('writable_dirs', []);


// Hosts

host('ec2-private')
    ->set('deploy_path', '/var/www/html/booking')
    ->port(22)
    ->configFile('C:/Users/utente/.ssh/config')
    ->identityFile('C:/Users/utente/.ssh/laravel-booking.pem')
    ->forwardAgent()
    ->user('ec2-user')
    ->stage('dev');


// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});


task("booking:install", function () {
    run('php {{release_path}}/artisan booing:install');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');

before('artisan:migrate', 'artisan:down');

after('deploy:symlink', 'artisan:up');


after("artisan:migrate", "booking:install");
