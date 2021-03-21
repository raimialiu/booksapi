<?php

namespace App\interfaces;

use App\Models\User;
use App\Models\Books;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\True_;

class BooksRepository implements IBooksRepository 
{

    private $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function AddNewUser(User $user)
    {
        $findUser = $user->where('email', $user->email)->first();

        if($findUser ==null) {
            $findUser = new User();
            $findUser->name = $user->name;
            $findUser->password = $user->password;
            $findUser->email = $user->email;

            $save = $findUser->save();

            if($save) {
                return true;
            }

            return false;
        }
    }

    public function CreateBooks($books)
    {
        $findUser = Books::where('Title', $books->Title)
                            ->where('userid', $books->userid)
                            ->first();
       
        if($findUser == null || empty($findUser)) {
            return false;
        }
/*
        $bk = new Books();
        $bk->Title = $books->Title;
        $bk->releasedYear = $books->releasedYear;
        $bk->Authtor = $books->Author;
        $bk->userid = $books->userid;
        */
       $result =  DB::table('books')->insert(['Title'=>$books->Title, 'Authtor'=>$books->Author, 
        'userid'=>$books->userid, 'releasedYear'=>$books->releasedYear]);
        //$chandesMade = $books->save();
        //echo(json_encode($result));
        // detect if changes was made

        if($result) {
            return true;
        }

        return false;
    }
    public function GetAllBooks()
    {
        return $this->books->all();
    }

}