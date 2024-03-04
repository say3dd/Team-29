<?php
//@noramknarf (Francis Moran) - getInfo() function
/* @KraeBM (Bilal Mohamed) worked on this page (pageupdate function) */
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Basket;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function productList()
    {
        //mistake fixed
        $products = Product::all();
        return view('product', compact('product'));
    }
    /** @BilalMo The page update page works on making sure the products are displayed a  */
    public function pageUpdate(Request $request,$id)
    {
      /** This retrieve the distinct data */
        $brands = $this->getDistinctBrands();
        $graphics = $this->getDistinctGPUs();

      /** This organises the sorting for the product page */
        $sorting = $request->input('sorting');
        $laptops = $this->sortLaptops($sorting);

        /** This handles the filtering side of the product page */
        $checkedBrands = $request->get('brands', []);
        $checkedGPU = $request->get('graphics', []);
        /*If the filtered laptops contain these checked items, and it true, make only those visible when laptop is called **/
        $filteredLaptops = $this->filterLaptops($checkedBrands, $checkedGPU);
        if ($filteredLaptops) {
            $laptops = $filteredLaptops;
        }
        /* Return the rendered page with the details required to show**/
        return $this->renderPage($id, compact('laptops', 'brands', 'graphics'));
    }
    /** @BilalMo Assigns the product to get the distinct Brands  */
    protected function getDistinctBrands()
    {
        return Product::select('brand')->distinct()->orderBy('brand')->get();
    }
    /** @BilalMo Assigns the product to get the distinct GPUs  */
    protected function getDistinctGPUs() {
        return Product::select('GPU')->distinct()->orderBy('GPU')->get();
    }

    /** @BilalMo Assigns the product to get the distinct prices  */
    protected function getDistinctPrices() {
        return Product::select('price')->distinct()->orderBy('price')->get();
    }

        /** @BilalMo Renders the page if available by only the Id */
        protected function renderPage($id, $data) {
            $viewPage = 'Product_files.products' . ($id > 1 ? $id : '');
            if (view()->exists($viewPage)) {
                return view($viewPage, $data);
            }
            return redirect()->back();
        }
        if($sorting == "Newest-Arrival") {
            $laptops = Product::orderby('created_at', 'Asc')->paginate(12);
        }

    /** @Bilal Mo Assigning operations for the sorting functions */
    protected function sortLaptops($sorting)
    {
        return match ($sorting) {
            "Price_LtoH" => Product::orderby('price', 'ASC')->paginate('12'),
            "Price_HtoL" => Product::orderby('price', 'Desc')->paginate('12'),
            "Newest-Arrival" => Product::orderby('created_at', 'Asc')->paginate(12),
            default => Product::all(),
        };
    }
    /** @BilalMo The function works on displaying the laptop based on certain conditionals whether the filter of the feature has been
     *pressed or not*/
    protected function filterLaptops($checkedBrands,$checkedGPU)
    {
        /**Assigning operations for if there are no filters chosen or
         * if both filters are chosen - here both selected in the request */

        if (!empty($checkedBrands) && !empty($checkedGPU)) {
           return Product::whereIn('brand', $checkedBrands)
                ->whereIn('GPU', $checkedGPU)->get();
        } elseif (!empty($checkedBrands)) {
            return Product::whereIn('brand', $checkedBrands)->get();
        } elseif (!empty($checkedGPU)) {
           return Product::whereIn('GPU', $checkedGPU)->get();
        }
        return null;
    }

    public function getInfo(Request $request)
    {
        /* had to restate these and put assign them within view since it returns an Undefined variable $brands/$graphics issue **/
        $brands = Product::select('brand')->distinct()-> orderby('brand')-> get();
        $graphics = Product::select('GPU')->distinct()-> orderby('GPU')-> get();

        $laptopID = request()->input('laptopData'); //grabs specifically the section of the request that holds the laptop's ID
        if($laptopID != '' && Auth::id() != null){
            $product_data = DB::table('products')->where('product_id', $laptopID)->first();

            $basket = Basket::create([
                'user_id' => Auth::id(),
                'product_id' => $laptopID,
                'product_name' => $product_data->laptop_name,
                'product_price' => $product_data->price,
                'image_path' =>$product_data->image_path,
                'RAM' => $product_data->RAM,
                'GPU' => $product_data->GPU,
                'processor' => $product_data->processor
            ]);
            /* In summary, $laptopID is the id passed to the controller by the products page,
            $product_data is the entire row from the products table for that product, any info needed can be accessed with -> then the column name in the products table
            I would have rather kept the specs somewhere else to prevent clutter but it's slightly more reliable just expanding the table and passing as usual*/
       }
       $laptops = Product::all();
       /*Scroll position set to the poisition of the user input */
       /*Sets the restore scroll originally to true, if its true, then page refreshes from the top, if not continues by using
       the saved Scroll positon */
       $scrollPosition = $request->input('scrollPosition');
       session(['scrollPosition' => $scrollPosition, 'restoreScroll' => true]);
       return redirect()->back();
    }

    // @say3dd (Mohammed Miah) displays all the products, maximum of 12 on the products page
    public function index()
    {
        $laptops = Product::paginate(12);
         return view('Product_files.product', compact('laptops'));

    }
    public function search(){
        $search = request()->query('search');
if ($search){
    $laptops = Product::where('laptop_name','LIKE',"%{$search}%")
        ->orwhere('price','LIKE',"%{$search}%")
        ->orwhere('brand','LIKE',"%{$search}%")
        ->simplepaginate(12);
}else{
    $laptops = Product::simplePaginate(12);
}
$brands = $this->getDistinctBrands();
$graphics = $this->getDistinctGPUs();
return view('Product_files.products',compact('laptops','brands','graphics'));
    }



    // @say3dd (Mohammed Miah) Function to show a maximum of 4 products on the home page, namely the "Our Laptops" section.


    // @say3dd (Mohammed Miah) Function to allow us to see related products on the individual product details page
    public function show($id)
    {
        $laptop = Product::find($id);
        $laptops = Product::where('product_id', '!=', $id)->take(5)->get();
        return view('Product_files.product', ['product' => $laptop, 'laptops' => $laptops]);
    }

}


