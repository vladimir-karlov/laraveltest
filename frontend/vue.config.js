module.exports = {
  "devServer": {
    "proxy": "http://laraveltest.site"
  },
  "outputDir": "../public/",
  "indexPath": "index.html",
  "transpileDependencies": [
    "vuetify"
  ],
  runtimeCompiler: true,
}