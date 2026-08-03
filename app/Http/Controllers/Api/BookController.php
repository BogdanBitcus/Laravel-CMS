<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Http\Requests\StoreBookRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{

    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new BookResource($book)
        ]);
    }



    public function store(StoreBookRequest $request): JsonResponse
    {
        try {

            $book = Book::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Book created.',
                'data' => new BookResource($book),
            ], 201);

        } catch (\Throwable $e) {

            Log::error('Book not created',[
                'error'=>$e->getMessage(),
                'request'=>$request->validated(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Some error. Try again later or write to our support',//$e->getMessage(),
            ],500);


        }
    }



    public function index()
    {
        //return Book::getAll();
        //return Book::paginate(20);
        return BookResource::collection(Book::paginate());
    }



    public function update(Request $request, Book $book): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
        ]);

        try {

            $book->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Book updated successfully.',
                'data' => new BookResource($book),
            ]);

        } catch (\Throwable $e) {

            Log::error('Book not updated', [
                'error'=>$e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Some error. Try again later or write to our support',//$e->getMessage(),
            ],500);

        }
    }



    public function destroy(Book $book): JsonResponse
    {

        try{

            $book->delete();

            return response()->json([
                'success'=>true,
                'message'=>'Book deleted'
            ]);

        }catch(\Throwable $e){

            Log::error('Book not deleted', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success'=>false,
                'message'=>'Cannot delete book'
            ],500);
        }
    }



}
