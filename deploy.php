<?php
namespace Deployer;

require 'recipe/symfony.php';

// Configuration de base
set('repository', 'git@github.com:username/repository.git');
set('branch', 'main');
set('shared_files', ['.env']);
set('shared_dirs', ['var/log']);
set('writable_dirs', ['var']);
set('allow_anonymous_stats', false);

// Hosts
host('77.237.244.33')
    ->set('remote_user', 'belha')
    ->set('deploy_path', '/var/www/lougar')
    ->set('port', 4898); // Spécifier le port ici

// Tâches
task('docker:start', function () {
    run('cd {{deploy_path}}/current && docker-compose -f docker-compose.prod.yml up -d');
});

task('docker:stop', function () {
    run('cd {{deploy_path}}/current && docker-compose -f docker-compose.prod.yml down');
});

task('database:migrate', function () {
    run('cd {{deploy_path}}/current && php bin/console doctrine:migrations:migrate --no-interaction');
});

after('deploy:symlink', 'docker:start');
after('deploy:failed', 'docker:stop');
before('deploy:symlink', 'database:migrate');
