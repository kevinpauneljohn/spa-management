<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetOwnerToDiscounts extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:discount-owner {owner}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the owner of a discount';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (DB::table('discounts')->where('owner_id',null)->update(['owner_id' => $this->argument('owner')]))
        {
            $this->info('Discounts owner has been set');
        }
        else{
            $this->info('Failed');
        }
    }
}
