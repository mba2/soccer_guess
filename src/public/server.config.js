var path = require('path');
module.exports = {
    inline: true,
    contentBase: path.join(__dirname, "src"),
    port: 3333,
    hot: true,
    filename: 'bundle.js',
    open: true,
    publicPath: "/assets/js",
    watchContentBase: true
}