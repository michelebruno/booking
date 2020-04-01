<?php

namespace Deployer;

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
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', ['vendor']);


set('writable_use_sudo', true);
// Hosts

host('ec2-private')
    ->set('deploy_path', '/home/ec2-user/booking')
    ->user('ec2-user')
    ->port(22)
    ->configFile('C:/Users/utente/.ssh/config')
    ->identityFile('C:/Users/utente/.ssh/laravel-booking.pem')
    ->forwardAgent();


// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'artisan:migrate');
