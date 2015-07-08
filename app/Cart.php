<?php

namespace App;

use App\CartItem;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{

    protected $fillable = ['user_id', 'quantity'];

    /**
     * Get the user that this cart belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Delete items in cart by items' id.
     *
     * @param $item_id
     *
     * @internal param $cart_items_id
     */

    public function remove($item_id)
    {
        CartItem::destroy($item_id);
    }

    /**
     * Check whether all items is valid in given cart; Return boolean;
     *
     * @return bool
     * @internal param $cart_id
     */
    public function isValid()
    {
        $cart_items = $this->cartItems();
        foreach ($cart_items as $item) {
            if ($item->product()->isSold()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Remove all sold items in cart.
     */
    public function validate()
    {
        foreach ($this->cartItems() as $item) {
            if ($item->product()->isSold()) {
                $this->remove($item->id);
            }
        }
    }

    /**
     * Get all cart items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('App\CartItem');
    }

    /**
     * Add an item into cart.
     *
     * @param Product $item
     *
     * @return CartItem
     */
    public function add(Product $item)
    {
        return CartItem::create([
            'cart_id'    => $this->id,
            'product_id' => $item->id,
        ]);
    }

    /**
     * Update the quantity of an cart item.
     *
     * @param $cart_item_id
     * @param $quantity
     */
    public function updateItem($cart_item_id, $quantity)
    {
        CartItem::find($cart_item_id)->update([
            'quantity'  => $quantity,
        ]);
    }

    /**
     * Remove all items from cart.
     */
    public function clear()
    {
        foreach ($this->cartItems as $cart_item)
        {
            $cart_item->delete();
        }
    }

    /**
     * Get the total price of all items.
     *
     * @return int
     */
    public function totalPrice()
    {
        $price = 0;

        foreach ($this->cartItems as $cart_item)
        {
            $price += $cart_item->product->price;
        }

        return $price;
    }

    /**
     * Check if cart has the given item.
     *
     * @param $item_id
     *
     * @return bool
     */
    public function hasItem($item_id)
    {
        return !$this->cartItems->where('id', $item_id)->isEmpty();
    }


}
