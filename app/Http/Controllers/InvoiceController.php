<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\invoice;
use App\Helper\SSLCommerz;
use App\Models\product_cart;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\invoice_product;
use App\Models\customer_profile;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function InvoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try{

           $user_id = $request->header('id');
            $user_email = $request->header('email');

            $tran_id = uniqid();
            $delivery_status = 'pending';
            $payment_status = 'pending';

            $profile = customer_profile::where('user_id','=',$user_id)->first();
            $cus_details = "Name:$profile->cus_name,Address:$profile->cus_add,City:$profile->cus_city,Phone:$profile->cus_phone";
            $ship_details = "Name:$profile->ship_name,Address:$profile->ship_add,City:$profile->ship_city,Phone:$profile->ship_phone";
                // payable Calculation
            $total = 0;

             $cartList = product_cart::where('user_id','=',$user_id)->get();
              foreach($cartList as $cartItem){
                $total =$total+$cartItem->price;
            }
           
            $vat =($total *3)/100;
            $payable = $total + $vat;
            // invoice Create
               $invoice = invoice::create([
             "total"=>$total,
             "vat"=>$vat,
             "payable"=>$payable,
             "cus_details"=>$cus_details,
             "ship_details"=>$ship_details,
             "tran_id"=>$tran_id,
             "delivery_status"=>$delivery_status,
             "payment_status"=>$payment_status,
             "user_id"=>$user_id
            ]);

             $invoiceID = $invoice->id;

          foreach($cartList as $EachProduct){
            invoice_product::create([
                 "invoice_id"=>$invoiceID,
                 "product_id"=>$EachProduct['product_id'],
                 "user_id"=>$user_id,
                 "qty"=>$EachProduct['qty'],
                 "sale_price"=>$EachProduct['price'],
            ]);
          }
      $paymentMethod = SSLCommerz::InitialPayment($profile,$payable,$tran_id,$user_email);

          DB::commit();
          return ResponseHelper::Out('success',array(['paymentMethod'=>$paymentMethod,'payable'=>$payable,'vat'=>$vat,'total'=>$total]),200);
        }catch(Exception $e){
         DB::rollBack();
         return ResponseHelper::Out('fail',$e,200);
        }
    }
    public function invoiceList(Request $request){
        $user_id=$request->header('id');
        return invoice::where('user_id',$user_id)->get();
    }
    public function invoiceProductList(Request $request){
        $user_id=$request->header('id');
        $invoice_id=$request->invoice_Id;
        return invoice_product::where(['user_id'=>$user_id,'invoice_id'=>$invoice_id])->with('product')->get();
    }

    public function paymentSuccess(Request $request){
           return SSLCommerz::InitiateSuccess($request->query('tran_id'));
    }

    public function paymentCencel(Request $request){
           return SSLCommerz::InitiateCancel($request->query('tran_id'));
    }

    public function paymentFail(Request $request){
            return SSLCommerz::InitiateFail($request->query("tran_id"));
    }
    public function paymentIpn(Request $request){
            return SSLCommerz::InitiateIPN($request->input('tran_id'),$request->input('status'),$request->input('val_id'));
    }
}
