module.exports = {
	productionSourceMap: false,
	filenameHashing: false,
	assetsDir: 'assets/',
	configureWebpack: {
		performance: {
			maxEntrypointSize: 512000,
			maxAssetSize: 512000
		},
	}
}