module.exports = [
    {
        loader: "style-loader"
    },
    
    {
        loader: "css-loader",
        options: {
            minimize: process.env.NODE_ENV === 'dist'
        }
    },

    {
        loader: "sass-loader"
    },

    {
        loader: "postcss-loader",
        options: {
            plugins: function() {
                var prefixer = require('autoprefixer');

                return [
                    prefixer({"browsers": "last 10 versions"})
                ]
            }
        }
    }
];