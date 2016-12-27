const path = require('path');
const args = require('minimist')(process.argv.slice(2));

// List of allowed environments
const allowedEnvs = ['dev', 'prod'];

// Set the correct environment
const env = args.env ? args.env : 'dev';

// Get available configurations
const configDir = path.join(__dirname, 'front/config');
var configs = {
  dev: () => require(path.join(configDir, 'dev.config')),
  prod: () => require(path.join(configDir, 'prod.config')),
};

/**
 * Get an allowed environment
 * @param  {String}  env
 * @return {String}
 */
function getValidEnv(env) {
  var isValid = env && env.length > 0 && allowedEnvs.indexOf(env) !== -1;
  return isValid ? env : 'dev';
}

/**
 * Build the webpack configuration
 * @param  {String} env Environment to use
 * @return {Object} Webpack config
 */
function buildConfig(env) {
  var usedEnv = getValidEnv(env);
  return configs[usedEnv]();
}

module.exports = buildConfig(env);
