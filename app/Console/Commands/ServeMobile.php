<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;
use function Laravel\Prompts\warning;


class ServeMobile extends Command
{
    protected $signature = 'mobile:serve {--android} {--ios}';


    public function handle()
    {
        if( ! $this->option( 'ios' ) && ! $this->option( 'android' ) ) return warning( "A device option is needed : 'mobile:serve --android' or 'mobile:serve --ios'" );

        $this->getOutput()->isVerbose() ? $this->call( 'ports:clear' ) : $this->callSilently( 'ports:clear' );

        intro( 'Running Mobile Environment' );

        $this->initTauriServer();
        $this->initViteServer();
        $this->initPHPServer();
    }


    private function initTauriServer() : void
    {
        $device = $this->option( 'ios' ) ? 'ios' : 'android';

        note( Str::headline( "Starting Mobile {$device} App" ) );

        if( ! File::exists( base_path( 'tauri/target' ) ) )
        {
            Process::path( 'tauri' )->forever()->tty()->run( "cargo build" );
        }

        if( ! File::exists( base_path( "tauri/gen/{$device}" ) ) )
        {
            Process::forever()->tty()->run( "npm run tauri {$device} init" );
        }

        if( ! File::exists( base_path( 'tauri/icons' ) ) )
        {
            Process::forever()->tty()->run( "npm run tauri icon tauri/icon.png" );
        }

        Process::tty()->start( "npm run dev:tauri:mobile:{$device}" );
    }

    private function initViteServer() : void
    {
        note( "Starting Vite Development Server" );

        Process::start( "npm run dev:vite:mobile" );
    }

    private function initPHPServer() : void
    {
        note( "Starting PHP Server" );

        Process::forever()->run( "php artisan serve --host='192.168.0.9' --port=2222" );
    }
}
