<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class UpdateRankingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rankings';

    public function handle()
    {
        $ambassadors = User::ambassadors()->get();

        $bar = $this->output->createProgressBar($ambassadors->count());

        $ambassadors->each(function (User $user) use($bar) {
            Redis::zadd('rankings', (int)$user->revenue, $user->name);

            $bar->advance();
        });

        $bar->finish();
    }
}
