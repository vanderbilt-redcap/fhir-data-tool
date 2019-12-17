// const fs = require('fs');
 
module.exports = {
    devServer: {
        overlay: {
            warnings: false,
            errors: true
        },
        proxy: {
            '/backend': {
                target: 'https://redcap.test/fhir-data-tool',
                ws: false,
                changeOrigin: true,
                pathRewrite: {'^/backend': ''}
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