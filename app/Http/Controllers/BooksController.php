<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\interfaces\IBooksRepository;
use App\Models\User;
use App\Models\Books;
use Exception;
use Illuminate\Support\Facades\DB;
use stdClass;

class BooksController extends Controller
{
    private $_repo;
    private $request;

    public function __construct(IBooksRepository $repo)
    {
        $this->_repo = $repo;
        
    }

    public function GetBookById(Request $request) 
    {
        try
        {
            $id = $request->query('id');

            if($id == null) {
                return response(json_encode(['status'=>'failed', 'data'=>"id is required and should be passed as query string"]), 400, ['Content-Type'=>'application/json']);
            }

            $book = DB::table('books')->where('id', $id)->get();

            if($book == null || count($book) == 0) {
                return response(json_encode(['status'=>'failed', 'data'=>"no match found for the specified id"]), 400, ['Content-Type'=>'application/json']);
            }

            return response(json_encode(['status'=>'success', 'data'=>$book]), 200, ['Content-Type'=>'application/json']);
        }
        catch(Exception $es) {
            return response(json_encode(['status'=>'failed', 'data'=>$es->getMessage()]), 500, ['Content-Type'=>'application/json']);
          }
    }

    public function DeleteBookById(Request $request) 
    {
        try{
            $id = $request->query('id');

            if($id == null) {
                return response(json_encode(['status'=>'failed', 'data'=>"id is required and should be passed as query string"]), 400, ['Content-Type'=>'application/json']);
            }
            $book = DB::table('books')->delete($id);

            if($book == null || $book == 0) {
                return response(json_encode(['status'=>'failed', 'data'=>"no match found for the specified id"]), 400, ['Content-Type'=>'application/json']);
            }
            

            return response(json_encode(['status'=>'success', 'data'=>'book deleted']), 200, ['Content-Type'=>'application/json']);
        }
        catch(Exception $es) {
            return response(json_encode(['status'=>'failed', 'data'=>$es->getMessage()]), 500, ['Content-Type'=>'application/json']);
          }

    }
    public function AddBook(Request $request) 
    {
        try {
                $this->request = $request;
                $bk = new Books();
                $bk->Title = $this->getJsonKey('Title');
                $bk->releasedYear = $this->getJsonKey('releasedYear');
                $bk->Author = $this->getJsonKey('Author');
                $bk->userid = $this->getJsonKey('userid');
                //$chandesMade = $this->_repo->CreateBooks($bk);
                $books = $bk;
                $BookDoesNotExist = DB::table('books')->where('Title', $bk->Title)->get();

                if(count($BookDoesNotExist) == 0) 
                {
                    $chandesMade = DB::table('books')->insert(['Title'=>$books->Title, 'Authtor'=>$books->Author, 
                        'userid'=>$books->userid, 'releasedYear'=>$books->releasedYear]);

                //var_dump(json_encode($chandesMade));
                    if($chandesMade == 1 || $chandesMade == true) 
                    {
                        return response(json_encode(['status'=>'success', 'data'=>'books created']), 200, ['Content-Type'=>'application/json']);
                    }

                    return response(json_encode(['status'=>'failed', 'data'=>'error create new book']), 400, ['Content-Type'=>'application/json']);

                }
                else {
                    return response(json_encode(['status'=>'failed', 'data'=>$bk->Title." already exist"]), 400, ['Content-Type'=>'application/json']);
                }

                
                
        }
        catch(Exception $es) {
                return response(json_encode(['status'=>'failed', 'data'=>$es->getMessage()]), 500, ['Content-Type'=>'application/json']);
        }
        
    }

    private function getJsonKey($keyName='') {
        return $this->request->json()->get($keyName);
    }

   
    public function AllBooks() 
    {
        try{

              $std = new stdClass();
            $std->status = "success";
            $data = $this->_repo->GetAllBooks();
            $std->data= $data;

            return response(json_encode($std), 200, ['Content-Type'=>'application/json']);
        }
        catch(Exception $es) {
            return response(json_encode(['status'=>'failed', 'data'=>$es->getMessage()]), 500, ['Content-Type'=>'application/json']);
          }
    }

}
