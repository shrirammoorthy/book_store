<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Book;
use Auth;
use App\User;
use App\Http\Controllers\Controller;
use Scriptotek\GoogleBooks\GoogleBooks;
use AntoineAugusti\Books\Fetcher;
use GuzzleHttp\Client;
class ListController extends Controller
{
    public function index()
    { 
        $id = \Auth::user()->id;
        if(Auth::user()){
            $items = Book::where('user_id', $id)->get();
            return view('list', compact('items'));
        }else{
            return redirect('/');
        }
    }
    public function create(Request $request)
    {
        $id = \Auth::user()->id;
        $book = new Book;
        $book->title = $request->text;
        $book->book_url = $request->link;
        $book->book_image = $request->img;
        $book->user_id = $id;
        $book->save();
        return 'created';
    }
    public function users(Request $request)
    {
        $items = User::all();
        return view('users', compact('items'));
    }  
    public function users_list($id)
    {
        $items = Book::where('user_id', $id)->get();;
        return view('users_list', compact('items'));
    } 
    public function delete(Request $request)
    {
        $item = Book::find($request->id);
        $item->delete();
        return "deleted";
    }   
    public function search(Request $request)
    {
       return view ('search');
    }
    public function book(Request $request)
    {
        $books = new GoogleBooks(['key' => env('GOOGLE_BOOKS_KEY')]);                   
        $items_array = array();
        if($request->title != ''){ 
            foreach ($books->volumes->search($request->title) as $books_key => $vol) {
                $item = Book::where('title', $vol->title)->get();
                if(!$item->isEmpty())
                {
                    $items_array[$books_key]['status']=1;
                }
                else{
                    $items_array[$books_key]['status']=0;
                }
                $items_array[$books_key]['title'] = $vol->title;
                $items_array[$books_key]['link'] = $vol->previewLink;
                if($vol->imageLinks != ''){
                    $items_array[$books_key]['image'] = $vol->imageLinks->thumbnail;
                }else{
                    $items_array[$books_key]['image'] = '#';
                }
                
           }
        }
        if($request->isbn != '')
        {
            $volume = $books->volumes->byIsbn($request->isbn);
            $item = Book::where('title', $volume->title)->get();
            if(!$item->isEmpty())
            {
                $items_array[0]['status']=1;
            }
            else{
                $items_array[0]['status']=0;
            }

            $items_array[0]['image'] = $volume->imageLinks->thumbnail;
            $items_array[0]['title'] = $volume->title;
            $items_array[0]['link'] = $volume->previewLink;          
        } 
        return view('book', compact('items_array'));
    }
}