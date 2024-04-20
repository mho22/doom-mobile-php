import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';


export default defineConfig( {
    plugins : [
        laravel( {
            input : [ 'resources/css/app.css', 'resources/ts/app.ts' ],
            refresh : true,
        } ),
        vue(),
    ],
    resolve : { alias : { '~': '/resources/ts' } },
    clearScreen : false,
    server : {
        host : "0.0.0.0",
        port : 5173,
        strictPort : true,
        hmr : {
            protocol : 'ws',
            host : "192.168.0.9",
            port : 5183,
        },
    }
} );
