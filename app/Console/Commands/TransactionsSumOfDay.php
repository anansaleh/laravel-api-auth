<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\TransactionsDailySum;
use App\Transaction;
use Illuminate\Console\Command;

class TransactionsSumOfDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:sum-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get thr sum of all transactions from previous day and stores it in transactions_daily_sum table';
    // TODO: run
    // crontab -e and add the follwing line
    // * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
    // 47 23 */2 * * cd /PATH/OF/YOUR/larvel_project/php artisan  transactions:sum-day >/dev/null 2>&1
    // 47 23 */2 * * cd /var/www/project/html/current/php artisan  transactions:sum-day >/dev/null 2>&1
    // In My vagrant (homestead) is
    // 47 23 */2 * * cd /home/vagrant/code/laravel_api/php artisan  transactions:sum-day >/dev/null 2>&1


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
        //get date
        $dfrom= Carbon::yesterday();
        $dend= Carbon::now();
        // $this->info( 'yestrday: '. $dfrom . ' now:' .$dend);
        $sum = Transaction::where('created_at', '>=', $dfrom)->get()->sum('amount');
        // $this->info( 'sum: '. $sum );

        $trans_sum = new TransactionsDailySum();
        $trans_sum->date_from=$dfrom;
        $trans_sum->sum_amount= $sum;
        $trans_sum->save();

    }
}
