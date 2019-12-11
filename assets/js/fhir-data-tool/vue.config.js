module.exports = {
    devServer: {
        overlay: {
            warnings: false,
            errors: true
        },
        proxy: {
            '/api': {
                target: 'https://redcap.test/fhir-data-tool',
                ws: true,
                changeOrigin: true,
                pathRewrite: {'^/api': ''}
            },
        }
    }
}