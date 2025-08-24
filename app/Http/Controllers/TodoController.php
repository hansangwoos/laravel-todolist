<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 모든 할일 가져오기
        $todos = Todo::all();

        // 최신순으로 가져오기
        // $todos = Todo::latest()->get();

        // 완료되지않은 일만 가져오기
        // $todos = Todo::where('is_completed', false)->get();

        return view('todos.index',compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $post = new Todo();
            $post->title = $request->title;
            $post->description = $request->description;
            $post->created_at = NOW();
            $post->updated_at = NOW();

            $post->save();

            $todos = Todo::all();
            return redirect()->route("todos.index")->with("success",'Todolist 추가 완료');

        } catch (\Throwable $th) {
            return redirect()->route("todos.index")->with("error",'Todolist 추가 오류');

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $todo = Todo::find($id);

        return view('todos.show',compact("todo"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $todo = Todo::find($id);

        return view("todos.edit",compact("todo"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        //
        try {
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->is_completed = $request->is_completed;

            $todo->save();

            return redirect()->route('todos.index')->with('success','수정이 완료 되었습니다.');

        } catch (\Throwable $th) {
            return back()->with('error','수정 중 오류 발생');

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $post = Todo::find($id);
        $post->delete();

        $todos = Todo::all();

        return redirect()->route("todos.index")->with("success","삭제가 완료 되었습니다.");
    }
}
