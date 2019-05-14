<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SendMailable;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Iuser;
use DateTime;

class SarlaftAboutToExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sarlaft:abouttoexpire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $date = new DateTime();
        $date->modify('+ 30 days');

        $more_date = $date->format('Y-m-d H:i:s');

        $sarlaftUsers = Iuser::where('sarlaf_duedate', '>=', Carbon::now())
        ->where('sarlaf_duedate','>',$more_date)
        ->select('iusers.*')
        ->get();

        Mail::to('nelsonlacouture@lasesores.com')->send(new SendMailable($sarlaftUsers));
    }
}
