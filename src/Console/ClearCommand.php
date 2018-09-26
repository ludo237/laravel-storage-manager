<?php

namespace Ludo237\LogsManager\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class ClearCommand
 *
 * @package Ludo237\LogsManager\Console
 */
final class ClearCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = "logs:clear";
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clear the logs located in storage/logs folder";
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "logs:clear {--F|force : Force the operation to run without confirmation}";
    
    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $customStoragePath
     */
    public function __construct(?Filesystem $customStoragePath)
    {
        parent::__construct();
        
        $this->setStoragePath($customStoragePath);
        $this->collectLogsFile();
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        $this->checkLogsPresence();
        $this->askForConfirmation();
        $this->performCleanUp();
    }
    
    /**
     * @return void
     */
    private function performCleanUp() : void
    {
        $countBefore = $this->logs->count();
        
        $this->logs->each(function (SplFileInfo $file) {
            $this->info("Removing: {$file->getFilename()}");
            File::delete($file->getRealPath());
        });
        
        $this->info("{$countBefore} files have been removed!");
    }
}
