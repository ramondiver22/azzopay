<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use UserPendingBalance;
use UserModel;
use ChargesModel;
use HistoryModel;

class payPendingBalances extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balances:pay-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Liberação de Saldos Pendentes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        
        try {
            

            $pendingBalances = UserPendingBalance::where("liquidation_date", "<=", date("Y-m-d") . " 23:59:59")->where("liquidated", "<", 1)->orderBy("id")->get();
            
            foreach ($pendingBalances as $pendingBalance) {
                
                try {
                    
                    $user = UserModel::where("user_id", $pendingBalance->user_id);
                    $user->updateBalance($user, $pendingBalance->amount, "credit");
                    $chargeDescription = $pendingBalance->description;
                    ChargesModel::registerCharge($user->id, $pendingBalance->entity_ref, $pendingBalance->charge, $chargeDescription);

                    HistoryModel::registerHistory($user->id, $amount, $pendingBalance->entity_ref, 0, 1);

                    $pendingBalance->liquidated = 1;
                    $pendingBalance->save();
                } catch (\Exception $ex) {
                    echo $ex->getTraceAsString();
                }

            }
            
        } catch (\Exception $ex) {
            echo $ex->getTraceAsString();
        }
        
        
        return 0;
    }
}