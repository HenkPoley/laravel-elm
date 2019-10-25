<?php

namespace Tightenco\Elm\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Artisan;

/**
 * Class Create
 * @package Tightenco\Elm\Commands
 */
class Create extends Command
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elm:create {program}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Elm program';

    /**
     * Create a new program creator command instance.
     *
     * @param  Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $program = \Str::studly($this->argument('program'));

        if (!is_dir('resources')) {
            $this->files->makeDirectory('resources/');
        }

        if (!is_dir('resources/elm')) {
            $this->files->makeDirectory('resources/elm/');
        }

        $this->files->makeDirectory('resources/elm/' . $program);

        $initialProgram = <<<EOT
module $program exposing (..)

import Html exposing (div, h1, text)

main : Html.Html a
main =
   div [] [ h1 [] [text "Hello, World!"] ]
EOT;

        // TODO: Look for elm.json file
        //   Missing? run `echo 'y' | elm init`
        //   Read elm.json and pick the first item in 'source-directories'
        
        // TODO: Add /resources/elm/elm-stuff to .gitignore
        $this->files->put("resources/elm/src/$program/Main.elm", $initialProgram);
        
        // TODO: Some kind of build script to build to public/{$program}/Elm.Main.init.js
        
        // TODO: create resources/views/{$program}.blade.php
        //   <!DOCTYPE HTML>
        //   <html>
        //   <head>
        //       <meta charset="UTF-8">
        //       <title>{$program}</title>
        //       <style>body {
        //               padding: 0;
        //               margin: 0;
        //           }</style>
        //   </head>
        //   
        //   <body>
        //   <script src="js/{$program}/Elm.Main.init.js"></script>
        //   
        //   {!! $elm !!}
        //   
        //   </body>
        //   </html>
        // TODO: create app/Http/Controllers/{$program}ElmController.php
        
        // TODO: Write to console what add to config/app.php
        //   `'providers' => [ .., Tightenco\Elm\ElmServiceProvider::class, ]` 
        //   `'aliases' => [ .., 'Elm' => Tightenco\Elm\ElmFacade::class, ]`
        
        // TODO: Write to console what to add to routes/web.php
        //   `Route::get('{$program}', '{$program}ElmController@index');`
        
        $this->info('Now run:');
        $this->line('$ cd resources/elm/');
        $this->line('$ elm init');
        $this->line('$ elm make ' . $program . '/Main.elm --output ' . $program . '.js');
    }
}
