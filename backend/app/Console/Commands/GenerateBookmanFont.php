<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use TCPDF_FONTS;
class GenerateBookmanFont extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'tcpdf:generate-bookman';
    protected $description = 'Generate Bookman Old Style font for TCPDF';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bookman = [
            "BOOKOS", "BOOKOSB", "BOOKOSBI", "BOOKOSI"
        ];
        foreach($bookman as $newFont){
            $path = resource_path('fonts/'.$newFont.'.TTF');
            if (!file_exists($path)) {
                $this->error("Font file not found at: $path");
                return 1;
            }
            $fontname = TCPDF_FONTS::addTTFfont($path, 'TrueTypeUnicode', '', 32);
            $this->info("Font generated: $fontname");
        }

        return 0;
    }
}
