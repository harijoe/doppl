const path = require('path');

module.exports = function (shipit) {
    require('shipit-deploy')(shipit);
    require('shipit-shared')(shipit);

    const workspace = '/tmp/shipit-workspace-doppl';
    const prod_parameters_path = 'app/config/parameters.yml';
    const server = 'www-data@54.93.73.250';
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

    shipit.task('copy_parameters', () => {
        return shipit.local(`cp ${path.join(__dirname, prod_parameters_path)}.prod ${workspace}/${prod_parameters_path}`);
    });


    shipit.blTask('run_composer_install', () => runRemotely([
        'export SYMFONY_ENV=prod && composer install --no-dev --optimize-autoloader',
        'php bin/symfony_requirements',
        'php bin/console cache:clear --env=prod --no-debug',
    ], shipit.releasePath)(shipit));

    shipit.blTask('run_npm_install', () => runRemotely([
        'npm install --production',
        'npm run build',
    ], shipit.releasePath)(shipit));

    shipit.on('fetched', function ( ) {
        shipit.start('copy_parameters');
    });

    shipit.on('updated', function () {
        shipit.start('run_composer_install');
        shipit.start('run_npm_install');
    });
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
