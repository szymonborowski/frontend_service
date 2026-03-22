import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import fs from 'fs';
import path from 'path';

export default defineConfig(({ mode }) => {
    const env     = loadEnv(mode, process.cwd(), '');
    const domain  = env.VITE_DOMAIN || 'microservices.local';
    const viteSub = `frontend-vite.${domain}`;

    const certKey  = path.resolve(__dirname, `certs/${viteSub}-key.pem`);
    const certPem  = path.resolve(__dirname, `certs/${viteSub}.pem`);
    const hasCerts = fs.existsSync(certKey) && fs.existsSync(certPem);

    return {
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            tailwindcss(),
        ],
        ...(hasCerts && {
            server: {
                https: {
                    key:  fs.readFileSync(certKey),
                    cert: fs.readFileSync(certPem),
                },
                host:       viteSub,
                port:       5174,
                strictPort: true,
                allowedHosts: ['all'],
                hmr: {
                    host:     viteSub,
                    protocol: 'wss',
                    port:     5174,
                },
                watch: {
                    ignored: ['**/storage/framework/views/**'],
                },
            },
        }),
    };
});
