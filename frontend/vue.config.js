const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true,
  devServer: {
    host: '0.0.0.0',
    port: 8080,
    watchFiles: {
      paths: ['src/**/*', 'public/**/*'],
      options: {
        usePolling: true,
        poll: 1000, 
        ignored: /node_modules/,
      },
    },
    hot: true, 
    client: {
      webSocketURL: 'auto://0.0.0.0:0/ws', 
    },
  },
  configureWebpack: {
    cache: false,
    watchOptions: {
      ignored: /node_modules/,
      poll: 1000, 
    },
  },
    chainWebpack: config => {
    config.plugin('define').tap(defs => {
      defs[0]['__VUE_PROD_HYDRATION_MISMATCH_DETAILS__'] = JSON.stringify(false)
      return defs
    })
  }
})