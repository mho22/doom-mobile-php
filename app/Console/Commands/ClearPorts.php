<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\intro;
use function Laravel\Prompts\note;


class ClearPorts extends Command
{
    protected $signature = 'ports:clear {port?*} {--audit}';

    public function handle() : void
    {
        intro( 'Clearing ports' );

        $ports = $this->argument( 'port' ) ?: [ 5173, 2222 ];

        foreach( $ports as $port )
        {
            $command = "lsof -i tcp:{$port} | awk 'NR>1 {print $2}'";

            $process = Process::run( $command, function( string $type, string $output ){ if( $type == 'err' || $this->getOutput()->isVerbose() ){ echo $output; } } );

            $pids = array_filter( explode( "\\n", trim( trim( json_encode( $process->output() ), "\"" ), "\\n" ) ) );

            empty( $pids ) ? note( "No Port {$port} used" ) : note( "Port {$port} :" );

            foreach( $pids as $pid ) if( ! empty( $pid ) ) $this->option( 'audit' ) ? note( "pid {$pid} active" ) : $this->clear( $pid );
        }
    }

    public function clear( int $pid ) : void
    {
        $command = "kill -9 {$pid}";

        Process::run( $command, function( string $type, string $output ){ if( $type == 'err' || $this->getOutput()->isVerbose() ){ echo $output; } } );

        note( "pid {$pid} killed" );
    }
}
