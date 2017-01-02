const path = require('path');
const program = require('commander');

program
    .option('-p, --provision', 'Triggers provisionning')
    .parse(process.argv);


module.exports = function (shipit) {
    require('shipit-deploy')(shipit);
    require('shipit-shared')(shipit);

    const server = [
        '35.157.9.185',
    ].map(ip => 'ubuntu@'+ip);

    const workspace = '/tmp/shipit-workspace-doppl';
    const prod_parameters_path = 'app/config/parameters.yml';
    const provisioning_path = 'devops/provisioning';
    const deployTo = '/var/www/doppl';

    shipit.initConfig({
        default: {
            workspace,
            deployTo,
            repositoryUrl: 'git@github.com:harijoe/doppl.git',
            ignores: ['.git', 'node_modules'],
            keepReleases: 3,
            deleteOnRollback: false,
            shallowClone: true,
            shared: {
                overwrite: true,
                dirs: [
                    './vendor',
                    './node_modules',
                ],
            },
        },
        prod: {
            servers: server
        }
    });

    /*
        Event listeners
     */
    shipit.on('deploy', () => {
        if (program.provision) {
            console.log('Provisionning started');
            shipit.start('start_provisioning');
        }
    });

    shipit.on('fetched', () => {
        shipit.start('copy_parameters');
    });

    shipit.on('updated', () => {
        shipit.start('run_composer_install');
        shipit.start('run_npm_install');
    });

    shipit.on('composer_installed', () => {
        shipit.start('give_www_data_permissions');
    });

    /*
        Tasks
     */
    shipit.task('copy_parameters', () => {
        return shipit.local(`cp ${path.join(__dirname, prod_parameters_path)}.prod ${workspace}/${prod_parameters_path}`);
    });

    shipit.blTask('run_composer_install', () => runRemotely([
        'export SYMFONY_ENV=prod && composer install --no-dev --optimize-autoloader',
        'php bin/symfony_requirements',
        'php bin/console cache:clear --env=prod --no-debug',
    ], shipit.releasePath)(shipit)
        .then(() => shipit.emit('composer_installed'))
    );

    shipit.blTask('run_npm_install', () => runRemotely([
        'npm install --production',
        'npm run build',
    ], shipit.releasePath)(shipit));

    shipit.blTask('start_provisioning', () => shipit.local(
        `ansible-playbook -i ${provisioning_path}/hosts ${provisioning_path}/index.yml`)
    );

    shipit.blTask('give_www_data_permissions', () => runRemotely([
        'sudo chgrp -R www-data .',
        'chmod -R 750 .',
        'chmod g+s .',
        'sudo chmod -R g+w var',
    ], shipit.releasePath)(shipit));
};

/*
    Utils
 */

const runRemotely = (cmds, path) => (shipit) => {
    if (!Array.isArray(cmds) || cmds.length === 0) { return null; }

    return cmds.reduce(
        (promiseChain, cmd) => promiseChain.then(() => shipit.remote(`cd ${path} && ${cmd}`)),
        Promise.resolve()
    );
};
