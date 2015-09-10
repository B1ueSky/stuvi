<?php namespace App\Http\Controllers\Textbook;

use Aloha\Twilio\Twilio;
use App\Address;
use App\Events\SellerOrderPickupWasScheduled;
use App\Events\SellerOrderWasCancelled;
use App\Http\Controllers\Controller;
use App\Listeners\EmailSellerOrderPickupConfirmation;
use App\SellerOrder;
use Auth;
use Cart;
use Config;
use DateTime;
use DB;
use Input;
use Log;
use Mail;
use Request;
use Response;
use Session;
use Validator;

class SellerOrderController extends Controller
{

    /**
     * Display a listing of seller orders for an user.
     *
     * @return Response
     */
    public function index()
    {
        $order = Input::get('ord');
        // check column existence
        $order = $this->hasColumn('seller_orders', $order) ? $order : 'id';

        return view('order.seller.index')
            ->with('seller_orders', Auth::user()->sellerOrders()->orderBy($order, 'DESC')->get());
    }

    /**
     * Display a specific seller order.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $seller_order = SellerOrder::find($id);

        // check if this order belongs to the current user.
        if (!is_null($seller_order) && $seller_order->isBelongTo(Auth::id()))
        {
            return view('order.seller.show')
                ->with('seller_order', $seller_order);
        }

        return redirect('order/seller')
            ->with('error', 'Order not found');
    }

    /**
     * Cancel a specific seller order.
     *
     * @param $id  The buyer order id.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        $v = Validator::make(Input::all(), [
            'seller_order_id'   => 'required|integer|exists:seller_orders,id',
            'cancel_reason'     => 'required|string'
        ]);

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $seller_order_id = Input::get('seller_order_id');
        $seller_order = SellerOrder::find($seller_order_id);
        $cancel_reason = Input::get('cancel_reason');

        // check if this order belongs to the current user.
        if ($seller_order->isBelongTo(Auth::id()))
        {
            if ($seller_order->isCancellable())
            {
                $seller_order->cancel(Auth::id(), $cancel_reason);

                event(new SellerOrderWasCancelled($seller_order));

                return redirect('order/seller/' . $seller_order_id)
                    ->with('success', 'Your order has been cancelled.');
            }
            else
            {
                return redirect('order/seller/' . $seller_order_id)
                    ->with('error', 'Sorry, this order cannot be cancelled.');
            }
        }

        return redirect('order/seller')
            ->with('error', 'Order not found.');
    }

    /**
     * Schedule pickup page.
     *
     * @param $seller_order_id
     * @return mixed
     */
    public function schedulePickup($seller_order_id)
    {
        $seller_order = SellerOrder::find($seller_order_id);

        return view('order.seller.schedulePickup')
            ->withSellerOrder($seller_order);
    }

    /**
     * The pickup has been confirmed and send an email to the seller about the pickup details.
     *
     * @param $id
     *
     * @return mixed
     */
    public function confirmPickup($id)
    {
        $seller_order = SellerOrder::find($id);

        $v = Validator::make(Input::all(), SellerOrder::confirmPickupRules());

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $seller_order->update([
            'address_id'            => Input::get('address_id'),
            'scheduled_pickup_time' => DateTime::createFromFormat(
                Config::get('app.datetime_format'), Input::get('scheduled_pickup_time'))
                ->format(Config::get('database.datetime_format'))
        ]);

        // send an email with a pickup verification code to the seller
        $seller_order->generatePickupCode();

        event(new SellerOrderPickupWasScheduled($seller_order));

        return redirect()->back()
            ->withSuccess("You have successfully updated the pickup and we'll email you the details shortly.");
    }

    /**
     * Transfer money of this order to seller's debit card
     */
    public function transfer()
    {
//        $seller_order_id = Input::get('seller_order_id');
//        $seller_order = SellerOrder::find($seller_order_id);
//
//        // check if this seller order belongs to the current user, or null.
//        if (empty($seller_order) || !$seller_order->isBelongTo(Auth::id()))
//        {
//            return redirect('/order/seller')
//                ->with('message', 'Order not found.');
//        }
//
//        // check if this seller order is transferred.
//        if ($seller_order->isTransferred())
//        {
//            return redirect('/order/seller/' . $seller_order_id)
//                ->with('message', 'You have already transferred the balance of this order to your Stripe account.');
//        }
//
//        // check if this seller order is delivered
//        if (!$seller_order->isDelivered())
//        {
//            return redirect('/order/seller/' . $seller_order_id)
//                ->with('message', 'This order is not delivered yet. You can get your money back once the buyer get the book.');
//        }
//
//        $credential = Auth::user()->stripeAuthorizationCredential;
//        // check if this user has a stripe authorization credential
//        if (empty($credential))
//        {
//            return redirect($this->buildStripeAuthRequestUrl());
//        }
//
//        \Stripe\Stripe::setApiKey(StripeKey::getSecretKey());
//
//        try
//        {
//            $transfer = \Stripe\Transfer::create([
//                'amount'             => ($seller_order->product->price),
//                'currency'           => Config::get('stripe.currency'),
//                'destination'        => $credential->stripe_user_id,
//                'application_fee'    => Config::get('stripe.application_fee'),
//                'source_transaction' => $seller_order->buyerOrder->buyer_payment->charge_id,
//            ]);
//
//            // save this transfer
//            $stripe_transfer = StripeTransfer::create([
//                'seller_order_id'     => $seller_order_id,
//                'transfer_id'         => $transfer['id'],
//                'object'              => $transfer['object'],
//                'amount'              => $transfer['amount'],
//                'currency'            => $transfer['currency'],
//                'status'              => $transfer['status'],
//                'type'                => $transfer['type'],
//                'destination'         => $transfer['destination'],
//                'application_fee'     => $transfer['application_fee'] ? : 0,
//                'balance_transaction' => $transfer['balance_transaction'],
//                'destination_payment' => $transfer['destination_payment'],
//                'source_transaction'  => $transfer['source_transaction'],
//            ]);
//
//            return redirect('/order/seller/' . $seller_order_id)
//                ->with('message', 'Balance has been transferred to your Stripe account. You can transfer it onto your bank account on Stripe.');
//
//        }
//        catch (\Stripe\Error\InvalidRequest $e)
//        {
//            // Invalid parameters were supplied to Stripe's API
//            $error2 = $e->getMessage();
//
//            return redirect()->back()
//                ->with('message', 'Sorry, transaction failed. Please contact Stuvi.');
//        }
//        catch (Exception $e)
//        {
//            // Something else happened, completely unrelated to Stripe
//            $error6 = $e->getMessage();
//        }
    }

    public function payout()
    {
        $seller_order_id = Input::get('seller_order_id');
        $seller_order = SellerOrder::find($seller_order_id);

        // check if this seller order belongs to the current user, or null.
        if (empty($seller_order) || !$seller_order->isBelongTo(Auth::id()))
        {
            return redirect('/order/seller')
                ->with('error', 'Order not found.');
        }

        // check if this seller order is transferred.
        if ($seller_order->isTransferred())
        {
            return redirect('/order/seller/' . $seller_order_id)
                ->with('error', 'You have already transferred the balance of this order to your Paypal account.');
        }

        // check if this seller order is delivered
        if (!$seller_order->isDelivered())
        {
            return redirect('/order/seller/' . $seller_order_id)
                ->with('error', 'This order is not delivered yet. You can get your money back once the buyer get the book.');
        }

        if ($seller_order->cancelled)
        {
            return redirect('/order/seller/' . $seller_order_id)
                ->with('error', 'This order has been cancelled.');
        }

        $payout_item = $seller_order->payout();
        if (!$payout_item)
        {
            redirect('/order/seller/'.$seller_order_id)
                ->with('error', 'Sorry, we cannot transfer the balance to your Paypal account. Please contact Stuvi.');
        }

        return redirect('/order/seller/'.$seller_order_id)
            ->with('success', 'The balance has been transferred to your paypal account '.$seller_order->seller()->profile->paypal);
    }


}
