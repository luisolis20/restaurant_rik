const path = require('path');
const { defineConfig } = require('@vue/cli-service');
const webpack = require('webpack');

module.exports = defineConfig({
  transpileDependencies: true,

  publicPath: process.env.NODE_ENV === 'production' ? '/biometrico/' : '/',
  outputDir: 'dist',
  assetsDir: 'assets',
  productionSourceMap: false,

  chainWebpack: config => {
    // ELIMINADO: Ya no necesitamos la regla 'ts' ni 'ts-loader'
    // Webpack por defecto ya sabe manejar .js y .vue
  },
  
  configureWebpack: {
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src/'),
      },
      // Simplificamos las extensiones (puedes quitar .ts y .tsx)
      extensions: ['.js', '.jsx', '.vue'], 
    },
    optimization: {
      minimize: true,
      splitChunks: {
        chunks: 'all',
      },
    },
    plugins: [
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
      }),
      new webpack.DefinePlugin({
       __API_RESTAURANT__: JSON.stringify(
          process.env.NODE_ENV === 'production'
            ? 'http://restaurant-back.test/api'
            : 'http://restaurant-back.test/api'
        ),
      }),
    ],
    output: {
      filename: 'assets/js/[name].[contenthash].js',
      chunkFilename: 'assets/js/[name].[contenthash].js',
    },
  },

  devServer: {
    proxy: {
      '/api': {
        target: 'http://restaurant-back.test',
        changeOrigin: true,
        pathRewrite: { '^/api': '' },
      },
    },
  },
});