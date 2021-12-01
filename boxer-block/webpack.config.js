const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");
 
module.exports = [{
  ...defaultConfig
},
// Config for view javacsript files
{
    entry: './src/view.js',
    output: {
        filename: 'view.js',
        path: __dirname + '/build'
    },
    module: {
        ...defaultConfig.module,
        rules: [
          ...defaultConfig.module.rules
        ]
    }
}];