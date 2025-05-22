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
        poll: 1000, // Verificar mudanças a cada segundo
        ignored: /node_modules/,
      },
    },
    hot: true, // Garantir que o hot reload está ativado
    client: {
      webSocketURL: 'auto://0.0.0.0:0/ws', // Ajuda com problemas de WebSocket no Docker
    },
  },
  configureWebpack: {
    cache: false,
    watchOptions: {
      ignored: /node_modules/,
      poll: 1000, // Consistente com a configuração acima
    },
  },
})