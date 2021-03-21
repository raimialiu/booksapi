<?php

namespace App\interfaces;

use App\Models\Books;
use App\Models\User;

interface IBooksRepository {

    public function AddNewUser(User $user);
    public function CreateBooks($books);
    public function GetAllBooks();
}