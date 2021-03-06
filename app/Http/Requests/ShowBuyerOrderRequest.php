<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class ShowBuyerOrderRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $buyer_order = $this->route('buyer_order');
        return $buyer_order->belongsToUser(Auth::id());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
