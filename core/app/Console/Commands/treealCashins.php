<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PixTreeal;
use GatewayModel;
use InvoiceModel;
use DepositsModel;
use DonationsModel;
use PaymentlinkModel;
use OrderModel;

    
class treealCashins extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'treeal:cashins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronização de transações de cashin da Treeal';

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

            $dateFrom = new \DateTime();
            $dateFrom->sub(new \DateInterval("P15D"));
            $dateTo = new \DateTime();

            $transactions = $pix->listarCobrancas(1, 200, 1, $dateFrom->format("Y-m-d"), $dateTo->format("Y-m-d"));


            if (isset($transactions->data->items)) {
                foreach($transactions->data->items as $pix) {

                    try {
                        $txid = $pix->receiverConciliationId;

                        $paymentInfo = (Object) Array(
                            "paymentMethod" => "pix",
                            "status" => 1,
                            "statusEnum" => "PAYMENT_CONFIRMED",
                            "statusDescription" => "Pagamento Confirmado",
                            "totalReceived" => number_format($pix->pix[0]->amount, 2, ".", ""),
                            "paymentId" => $pix->pix[0]->endToEndId,
                            "json" => json_encode($pix),
                            "transactionId" => $txid,
                            "gateway" => "treeal"
                        );


                        $invoice = InvoiceModel::where("pix_transaction_id", $txid)->first();
                    
                        if ($invoice) {

                            if ($invoice->status == 0) {
                                $invoice->proccessPayment($paymentInfo, "pix", null);
                            }
                            
                        } else {

                            $deposit = DepositsModel::where("pix_transaction_id", $txid)->first();

                            if ($deposit) {
                                if ($deposit->status == 0) {
                                    $deposit->proccessPayment($paymentInfo, "pix");
                                }
                            } else {

                                $donation = DonationsModel::where("pix_transaction_id", $txid)->first();

                                if ($donation) {

                                    if ($donation->status == 0) {
                                        $paymentLink = PaymentlinkModel::where("id", $donation->donation_id)->first();
                                        $paymentLink->proccessPayment($paymentInfo, "pix", null);
                                    }

                                } else {

                                    $paymentLink = PaymentlinkModel::where("pix_transaction_id", $txid)->first();

                                    if ($paymentLink) {

                                        if ($paymentLink->status == 0) {
                                            $paymentLink->proccessPayment($paymentInfo, "pix", null);
                                        }

                                    } else {

                                        $order = OrderModel::where("pix_transaction_id", $txid)->first();

                                        if ($order) {
                                            if ($order->status == 0) {
                                                $order->proccessPayment($paymentInfo, "pix", null);
                                            }
                                        }

                                    }

                                }
                                
                            }

                        }

                    } catch(\Exception $ex) {
                        echo $ex->getMessage() . PHP_EOL . PHP_EOL;
                        echo $ex->getTraceAsString() . PHP_EOL . PHP_EOL;
                    }

                }

            }
            
            
        } catch (\Exception $ex) {
            echo $ex->getMessage() . PHP_EOL . PHP_EOL;
            echo $ex->getTraceAsString() . PHP_EOL . PHP_EOL;
        }
        
        
        return 0;
    }
}
