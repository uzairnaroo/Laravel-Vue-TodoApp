const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  devServer: {
    historyApiFallback: true,
  },
  transpileDependencies: true
})
