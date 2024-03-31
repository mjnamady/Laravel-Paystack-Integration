<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaystackController extends Controller
{
    public function callback(Request $request){
        // dd($request->all());
        $reference = $request->reference;

        $secret_key = env('PAYSTACK_SECRET_KEY');
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $secret_key",
            "Cache-Control: no-cache",
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $response = json_decode($response);
        // dd($response->data->metadata->custom_fields);
        $meta_data = $response->data->metadata->custom_fields;

        if ($response->data->status == 'success') {
            
            $payment = new Payment();
            $payment->payment_id = $reference;
            $payment->product_name = $meta_data[0]->value;
            $payment->quantity = $meta_data[1]->value;
            $payment->amount = $response->data->amount / 100;
            $payment->currency = $response->data->currency;
            $payment->payment_status = "Completed";
            $payment->payment_method = "Paystack";
            $payment->save();

            return redirect()->route('success');
        } else {
            return redirect()->route('cancel');
        }
  
        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     echo $response;
        // }
    } // End Method

    public function success(){
        return "Payment is Successful!";
    }

    public function cancel(){
        return "Payment is cancelled!";
    }
}
