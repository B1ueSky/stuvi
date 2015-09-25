<?php namespace App;

use App\Helpers\Price;
use Aws\Laravel\AwsFacade;
use DB;
use File;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

class Book extends Model
{

    protected $fillable = [
        'title',
        'edition',
        'isbn10',
        'isbn13',
        'num_pages',
        'verified',
        'language',
        'list_price',
        'lowest_price',
        'highest_price'
    ];

    /**
     * Get textbook authors
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authors()
    {
        return $this->hasMany('App\BookAuthor');
    }

    /**
     * Get textbook image set
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function imageSet()
    {
        return $this->hasOne('App\BookImageSet');
    }

    /**
     * Get textbook products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * Get all products of this book that are not sold yet.
     *
     * @return mixed
     */
    public function availableProducts()
    {
        $products = $this->products()
            ->where('sold', 0)
            ->whereNull('deleted_at')
            ->join('product_conditions as cond', 'products.id', '=', 'cond.product_id')
            ->orderBy('cond.general_condition')
            ->select('products.*')
            ->get();

        return $products;
    }

    /**
     * Get lowest price in two decimal places.
     *
     * @return string
     */
    public function decimalLowestPrice()
    {
        return Price::convertIntegerToDecimal($this->lowest_price);
    }

    /**
     * Get highest price in two decimal places.
     *
     * @return string
     */
    public function decimalHighestPrice()
    {
        return Price::convertIntegerToDecimal($this->highest_price);
    }

    /**
     * Update book price range for adding a product price.
     * Used for adding new product or cancelling a seller order.
     *
     * @param integer $price
     * @return bool
     */
    public function addPrice($price)
    {
        // if both are not set, set them to the same price
        if ($this->lowest_price == null && $this->highest_price == null) {
            $this->update([
                'lowest_price' => $price,
                'highest_price' => $price
            ]);

            return true;
        }

        // update lowest price
        if ($this->lowest_price && $price < $this->lowest_price) {
            $this->update(['lowest_price' => $price]);
        }
        // update highest price
        if ($this->highest_price && $price > $this->highest_price) {
            $this->update(['highest_price' => $price]);
        }

        return false;
    }

    /**
     * Update book price range for removing a product price.
     * Used for deleting a product or selling a product.
     *
     * @param $price
     * @return bool
     */
    public function removePrice($price)

    {
        // update the lowest price
        if ($price == $this->lowest_price)
        {
            $this->update(['lowest_price' => $this->products()->where('sold', false)->get()->min('price')]);

        }

        // update the highest price
        if ($price == $this->highest_price)
        {
            $this->update(['highest_price' => $this->products()->where('sold', false)->get()->max('price')]);

        }

        // do nothing
        return true;
    }

    /**
     * Search for books that can be delivered to buyer's university given query title and buyer id.
     *
     * @param $query
     * @param $buyer_id
     * @return mixed
     */
    public static function queryWithBuyerID($query, $buyer_id)
    {
        $terms = explode(' ', $query);
        $clauses = array();

        foreach ($terms as $term) {
            $clauses[] = 'title LIKE "%' . $term . '%" OR a.full_name LIKE "%' . $term . '%"';
        }

        $filter = implode(' OR ', $clauses);

        $books = Book::whereRaw($filter)
            ->join('book_authors as a', 'a.book_id', '=', 'books.id')
            ->join('products as p', 'p.book_id', '=', 'books.id')
            ->join('users as seller', 'seller.id', '=', 'p.seller_id')
            ->whereIn('seller.university_id', function ($q) use ($buyer_id) {
                $q->select('uu.from_uid')
                    ->from(DB::raw('users as buyer, university_university as uu'))
                    ->where('buyer.id', '=', $buyer_id);
            })
            ->whereIn('seller.university_id', function ($q) {
                $q->select('id')
                    ->from('universities')
                    ->where('is_public', '=', true);
            })
            ->where('is_verified', true)
            ->select('books.*')->distinct()->get();

        return $books;
    }

    /**
     * Search for books that can be delivered to a specific university given query title and university id.
     *
     * @param $query
     * @param $university_id
     * @return mixed
     */
    public static function queryWithUniversityID($query, $university_id)
    {
        $terms = explode(' ', $query);
        $clauses = array();

        foreach ($terms as $term) {
            $clauses[] = 'title LIKE "%' . $term . '%" OR a.full_name LIKE "%' . $term . '%"';
        }

        $filter = implode(' OR ', $clauses);

        $books = Book::whereRaw($filter)
            ->join('book_authors as a', 'a.book_id', '=', 'books.id')
            ->join('products as p', 'p.book_id', '=', 'books.id')
            ->join('users as seller', 'seller.id', '=', 'p.seller_id')
            ->whereIn('seller.university_id', function ($q) use ($university_id) {
                $q->select('from_uid')->distinct()
                    ->from('university_university')
                    ->where('to_uid', '=', $university_id);
            })
            ->whereIn('seller.university_id', function ($q) {
                $q->select('id')
                    ->from('universities')
                    ->where('is_public', '=', true);
            })
            ->where('is_verified', true)
            ->select('books.*')->distinct()->get();

        return $books;
    }

    /**
     * Create a book according to the data from Google Book API.
     *
     * @param $google_book
     *
     * @return Book
     */
    public static function createFromGoogleBook($google_book)
    {
        // save this book to our database
        $book = Book::create([
            'isbn10'        => $google_book->getIsbn10(),
            'isbn13'        => $google_book->getIsbn13(),
            'title'         => $google_book->getTitle(),
            'language'      => $google_book->getLanguage(),
            'num_pages'     => $google_book->getPageCount(),
            'description'   => $google_book->getDescription(),
        ]);

        $book_image_set = new BookImageSet();
        $book_image_set->book_id = $book->id;
        $book_image_set->save();

        $temp_path = config('image.temp_path');
        $image_url = $google_book->getThumbnail();

        if ($image_url)
        {
            $image_path = $temp_path . 'temp.jpeg';
            $image = Image::make($image_url)->save($image_path);
            $image_filename = $book_image_set->generateFilename($size=null, $image);

            $book_image_set->update([
                'small_image'   => $image_filename,
                'medium_image'  => $image_filename,
                'large_image'   => $image_filename
            ]);

            $s3 = AwsFacade::createClient('s3');
            $bucket = app()->environment('production') ? config('aws.buckets.book_image') : config('aws.buckets.test_book_image');

            // upload images to amazon s3
            $s3->putObject(array(
                'Bucket'        => $bucket,
                'Key'           => $image_filename,
                'SourceFile'    => $image_path,
                'ACL'           => 'public-read'
            ));

            File::delete($image_path);
        }

        // save book authors
        foreach ($google_book->getAuthors() as $author_name) {
            BookAuthor::create([
                'book_id'   => $book->id,
                'full_name' => $author_name
            ]);
        }

        return $book;
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public static function rules()
    {
        $rules = array(
            'isbn'      => 'required',
            'title'     => 'required|string',
            'authors'   => 'required|string',
            'edition'   => 'required|integer|min:1',
            'num_pages' => 'required|integer|min:1',
            'language'  => 'required|string'
        );

        $rules['image'] = 'required|mimes:jpeg,png|max:5120';

        return $rules;
    }

}
