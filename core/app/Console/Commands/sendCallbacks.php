<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PendingCallback;

class sendCallbacks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:send-callbacks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de Callbacks';

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
        ob_start();
        try {
            $dateLimit = new \DateTime(date("Y-m-d H:i:s"));
            $dateLimit->sub(new \DateInterval("PT15M"));

            $callbacks = PendingCallback::where(function ($query) {
                $query->whereNotIn("http_response_code", ['200', '201', '202', '203', '204'])->orWhere("http_response_code", null);
            })->where("tries", "<", 10)->where(function ($query) use ($dateLimit) {
                $query->where("last_try", null)->orWhere("last_try", "<", $dateLimit->format("Y-m-d H:i:s"));
            })->orderBy("id")->get();
            
            foreach ($callbacks as $notification) {
                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $notification->host_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADER =>  true,
                    CURLOPT_NOBODY => false,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $notification->body,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Accept-Encoding: *"
                    ),
                ));

                $response = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                curl_close($curl);

                $notification->tries++;
                $notification->last_try = date("Y-m-d H:i:s");
                $notification->http_response_code = $httpcode;
                //$not->body_content = json_encode($dados);
                $notification->http_response_body = $response;
                if ($notification->first_try == null) {
                    $notification->first_try = date("Y-m-d H:i:s");
                }

                $notification->save();
            }
            
            
            $code = "200";
            $message = "ok";
        } catch (\Exception $ex) {
            $code = "500";
            $message = $ex->getMessage();
            echo $message;
            echo $ex->getTraceAsString();
        }
        
        
        $content = ob_get_contents();
        ob_end_clean();
        
        if (!empty(trim($content))) {
            if (!file_exists("callbacks_sent")) {
                mkdir("callbacks_sent");
            }
            
            $file = time();
            $arquivo = fopen("callbacks_sent/{$file}.txt",'w');
            fwrite($arquivo, $content);
            //Fechamos o arquivo após escrever nele
            fclose($arquivo);
        }
        
        header("HTTP/1.0 {$code} {$message}");
        echo "{$code} - {$message}";
        return 0;
    }
}
