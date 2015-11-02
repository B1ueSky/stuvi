<?php namespace App\Http\Controllers\Textbook;

use App\BuyerOrder;
use App\Helpers\Price;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Product;
use App\ProductCondition;
use App\ProductImage;
use App\SellerOrder;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Input;
use Response;
use Session;
use URL;
use Validator;

class ProductController extends Controller
{
    /**
     * The page for book confirmation after sell search.
     *
     * @param $book
     * @return \Illuminate\View\View
     */
    public function confirm($book)
    {
        return view('product.confirm')
            ->withBook($book);
    }

    /**
     * Show the form for creating a new product.
     *
     * @return Response
     */
    public function create($book)
    {
        if (Auth::check())
        {
            return view('product.create')
                ->withBook($book)
                ->withPaypal(Auth::user()->profile->paypal);
        }
        else
        {
            Session::flash('warning', 'Please login or signup to sell your book.');

            return view('product.create')
                ->withBook($book);
        }

    }

    /**
     * AJAX: Store a product.
     *
     * @return Response
     */
    public function store()
    {
        $images = Input::file('file');
        $sell_to = Input::get('sell_to');
        $payout_method = Input::get('payout_method');

        // validation
        $v = Validator::make(Input::all(), Product::rules($images));

        if ($v->fails())
        {
            return Response::json([
                'success' => false,
                'fields' => $v->errors()
            ]);
        }

        // update user's Paypal email address
        if ($payout_method == 'paypal')
        {
            Auth::user()->profile->update([
                'paypal'    => Input::get('paypal')
            ]);
        }

        $product = Product::create([
            'book_id'   => Input::get('book_id'),
            'seller_id' => Auth::user()->id,
            'available_at' => Carbon::parse(Input::get('available_at')),
            'sell_to'   => $sell_to,
            'payout_method' => $payout_method
        ]);

        // if sell to users, add product price
        if ($sell_to == 'users')
        {
            $int_price = Price::ConvertDecimalToInteger(Input::get('price'));
            $product->price = $int_price;
            $product->verified = true;
            $product->save();
            $product->book->addPrice($int_price);
        }

        $condition = ProductCondition::create([
            'product_id' => $product->id,
            'general_condition' => Input::get('general_condition'),
            'highlights_and_notes' => Input::get('highlights_and_notes'),
            'damaged_pages' => Input::get('damaged_pages'),
            'broken_binding' => Input::get('broken_binding'),
            'description' => Input::get('description'),
        ]);

        // save multiple product images
        foreach ($images as $image)
        {
            // create product image instance
            $product_image             = new ProductImage();
            $product_image->product_id = $product->id;
            $product_image->save();

            // save product image paths with different sizes
            $product_image->small_image  = $product_image->generateFilename('small', $image);
            $product_image->medium_image = $product_image->generateFilename('medium', $image);
            $product_image->large_image  = $product_image->generateFilename('large', $image);
            $product_image->save();

            // resize image
            $product_image->resize($image);

            // upload image with different sizes to aws s3
            $product_image->uploadToAWS();
        }

        return Response::json([
            'success' => true,
            'redirect' => '/textbook/buy/product/' . $product->id,
        ]);
    }

    /**
     * Display the specified product.
     *
     * @param Requests\ShowProductRequest $request
     * @param Product $product
     *
     * @return Response
     */
    public function show(Requests\ShowProductRequest $request, $product)
    {
        if (!$request->authorize())
        {
            return redirect('textbook/buy')
                ->with('error', 'This book is not available.');
        }

        return view('product.show')
            ->withProduct($product)
            ->withQuery(Input::get('query'))
            ->with('university_id', Input::get('university_id'));
    }

    /**
     * Show product edit page.
     *
     * @param Product $product
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function edit($product)
    {
        if (!($product && $product->isBelongTo(Auth::id())))
        {
            return back()
                ->with('error', 'Sorry, the product is not found.');
        }
        elseif ($product->sold)
        {
            return back()
                ->with('error', 'Product is sold.');
        }
        elseif ($product->isDeleted())
        {
            return back()
                ->with('error', 'Product is archived.');
        }

        return view('product.edit')
            ->with('book', $product->book)
            ->with('product', $product)
            ->with('paypal', Auth::user()->profile->paypal);
    }

    /**
     * Update product info.
     *
     * If AJAX, we'll update images.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $product = Product::find(Input::get('product_id'));
        $images = Input::file('file');

        // validation
        $v = Validator::make(Input::all(), Product::rules($images));

        $v->after(function($v) use ($product)
        {
            if (!($product || $product->isBelongTo(Auth::id())))
            {
                $v->errors()->add('product', 'The product is not found.');
            }
            elseif ($product->sold)
            {
                $v->errors()->add('product', 'The product was sold');
            }
            elseif ($product->isDeleted())
            {
                $v->errors()->add('product', 'The product is archived.');
            }
        });

        if ($v->fails())
        {
            if ($request->ajax())
            {
                return Response::json([
                    'success' => false,
                    'fields' => $v->errors(),
                ]);
            }
            else
            {
                return redirect()->back()
                        ->withErrors($v->errors());
            }

        }

        $payout_method = Input::get('payout_method');
        $sell_to = Input::get('sell_to');
        $old_price = $product->price;
        $int_price = Price::ConvertDecimalToInteger(Input::get('price'));
        $new_available_at = Carbon::parse(Input::get('available_at'));

        if ($sell_to == 'users')
        {
            $product->update([
                'verified'      => true,
                'price'         => $int_price,
                'available_at'  => $new_available_at,
                'sell_to'       => $sell_to,
                'payout_method' => $payout_method
            ]);

            // remove old price if it exists
            if ($old_price)
            {
                $product->book->removePrice($old_price);
            }

            $product->book->addPrice($int_price);
        }

        if ($sell_to == 'stuvi')
        {
            $product->update([
                'verified'      => false, // stuvi needs to verify this product
                'price'         => null,
                'available_at'  => $new_available_at,
                'sell_to'       => $sell_to,
                'payout_method' => $payout_method
            ]);

            // remove old price if it exists
            if ($old_price)
            {
                $product->book->removePrice($old_price);
            }
        }

        // update user's Paypal email address
        if ($payout_method == 'paypal')
        {
            Auth::user()->profile->update([
                'paypal'    => Input::get('paypal')
            ]);
        }

        // update product condition
        $product->condition->update([
            'general_condition' => Input::get('general_condition'),
            'highlights_and_notes' => Input::get('highlights_and_notes'),
            'damaged_pages' => Input::get('damaged_pages'),
            'broken_binding' => Input::get('broken_binding'),
            'description' => Input::get('description'),
        ]);

        // update condition
//        $condition = array_filter(Input::only(
//            'general_condition',
//            'highlights_and_notes',
//            'damaged_pages',
//            'broken_binding',
//            'description'), function($element)
//        {
//            return !is_null($element);      // filter out null values.
//        });
//
//        $product->condition->update($condition);


        // if AJAX request, save images
        if ($request->ajax())
        {
            foreach ($images as $image)
            {
                // create product image instance
                $product_image = new ProductImage();
                $product_image->product_id = $product->id;
                $product_image->save();

                // save product image paths with different sizes
                $product_image->small_image = $product_image->generateFilename('small', $image);
                $product_image->medium_image = $product_image->generateFilename('medium', $image);
                $product_image->large_image = $product_image->generateFilename('large', $image);
                $product_image->save();

                // resize image
                $product_image->resize($image);

                // upload image with different sizes to aws s3
                $product_image->uploadToAWS();
            }

            return Response::json([
                'success' => true,
                'redirect' => '/textbook/buy/product/' . $product->id,
            ]);
        }
        else
        {
            // if the request is not AJAX (Dropzone does not contain any image)
            // we do not need to save any image, just redirect to the product page
            return redirect('/textbook/buy/product/' . $product->id)
                ->with('success', 'The product is updated successfully.');
        }
    }

    /**
     * Delete a product record.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy()
    {
        if (!Input::has('id'))
        {
            return redirect('/user/bookshelf')
                ->with('error', 'Please enter a valid product id.');
        }

        $product = Product::find(Input::get('id'));

        // check if it belongs to the current user.
        if (!($product && $product->isBelongTo(Auth::id())))
        {
            return redirect('/user/bookshelf')
                ->with('error', 'Please enter a valid product id.');
        }

        // check if it is sold.
        if ($product->sold)
        {
            return redirect('/user/bookshelf')
                ->with('error', $product->book->title.' cannot be deleted because it is sold.');
        }

        $book = $product->book;
        $price = $product->price;

        // soft delete.
        $product->update([
            'deleted_at' => Carbon::now(),
                         ]);

        // update book's lowest or highest price if necessary
        $book->removePrice($price);

        return redirect('/user/bookshelf')
            ->with('success', $product->book->title.' has been deleted.');
    }

    /**
     * AJAX: get product images.
     *
     * @return mixed
     */
    public function getImages()
    {
        $product = Product::find(Input::get('product_id'));
        $product_images = $product->images;

        return Response::json([
            'success'   => true,
            'env'       => app()->environment(),
            'images'    => $product_images
        ]);
    }

    /**
     * AJAX: delete a product image according to the product image ID.
     *
     * @return mixed
     */
    public function deleteImage()
    {
        $product_image = ProductImage::find(Input::get('productImageID'));
        $product_image->deleteFromAWS();
        $product_image->delete();

        return Response::json([
            'success'   => true
        ]);
    }
}
