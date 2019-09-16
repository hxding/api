<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class SyncReceiptBankCommand extends Command
{
  
    protected $signature = 't_sync_repceipt_bank';

    public function __construct()
    {
    	parent::__construct();
    }

    public function handle()
    {
    	echo 't_sync_repceipt_bank';
    }

}