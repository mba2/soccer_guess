module.exports = [
    {
        loader: 'babel-loader',
        options: {
            presets: ['react', 'es2015']
        }
    },

    {
        loader: 'eslint-loader'
    }
]