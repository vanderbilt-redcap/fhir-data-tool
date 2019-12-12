// const fs = require('fs');
 
module.exports = {
    devServer: {
        overlay: {
            warnings: false,
            errors: true
        },
        proxy: {
            '/api': {
                target: 'https://redcap.test/fhir-data-tool/api',
                ws: true,
                changeOrigin: true,
                pathRewrite: {'^/api': ''}
            },
        },
        /* https: {
            // ca: fs.readFileSync('/Users/delacqf/code/CERTS/Biondo.pem'),
            // key: fs.readFileSync('/Users/delacqf/code/CERTS/localhost.key.pem'),
            // cert: fs.readFileSync('/Users/delacqf/code/CERTS/localhost.crt.pem'),
            // pfx: '',
        } */
    }
}