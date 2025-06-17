<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WithdrawModel;
use PixTreeal;
use GatewayModel;

class treealCashouts extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'treeal:cashouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronização de transações de cashout da Treeal';

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
    public function handle() {

        try {
            $gateway = GatewayModel::where("name", "Treeal")->first();
            $pix = new PixTreeal($gateway->val1, $gateway->sandbox > 0);

            $date = new \DateTime();
            $date->sub(new \DateInterval("P10D"));
            $today = new \DateTime();

            while($date->getTimestamp() <= $today->getTimestamp()) {
                $page  = 1;

                while($page > 0) {
                    $transactions = $pix->listarPagamentos($page, 0, $date->format("Y-m-d"));


                    if (isset($transactions->data->pixCashOutResponses)) {
                        foreach($transactions->data->pixCashOutResponses as $pix) {

                            try {

                                $txid = $pix->key;

                                $withdraw = Withdraw::where("pix_txid", $txid)->first();
                                if ($withdraw) {
                
                                    if ($pix->status == "COM ERRO") {
                                        $withdraw->cancelWithdraw($pix->errorMessage);
                                    } else if ($pix->status == "SUCESSO") {
                                        if ($withdraw->status == 0) {
                                            $withdraw->confirmPayment($pix->endToEnd);
                                        }
                                    }
                                    
                                }

                            } catch(\Exception $ex) {
                                echo $ex->getMessage() . PHP_EOL . PHP_EOL;
                                echo $ex->getTraceAsString() . PHP_EOL . PHP_EOL;
                            }

                        }

                    }

                    if ($page < $transactions->data->totalPages) {
                        $page++;
                    } else {
                        $page = 0;
                    }

                }

                $date->add(new \DateInterval("P1D"));
            }
            
        } catch (\Exception $ex) {
            echo $ex->getMessage() . PHP_EOL . PHP_EOL;
            echo $ex->getTraceAsString() . PHP_EOL . PHP_EOL;
        }
        
        return 0;
    }
}
