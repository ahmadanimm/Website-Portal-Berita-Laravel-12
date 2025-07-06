<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $authors = $query->latest()->paginate(10);

        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $avatar = $request->file('avatar')?->store('avatars', 'public');

        Author::create([
            'name' => $request->name,
            'avatar' => $avatar,
        ]);

        return redirect()->route('admin.authors.index')->with('success', 'Author berhasil ditambahkan.');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($author->avatar) {
                \Storage::disk('public')->delete($author->avatar);
            }
            $author->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $author->name = $request->name;
        $author->save();

        return redirect()->route('admin.authors.index')->with('success', 'Author berhasil diperbarui.');
    }

    public function destroy(Request $request)
    {
        $ids = explode(',', $request->selected_ids);

        foreach ($ids as $id) {
            $author = Author::find($id);
            if ($author) {
                if ($author->avatar) {
                    \Storage::disk('public')->delete($author->avatar);
                }
                $author->delete();
            }
        }

        return redirect()->route('admin.authors.index')->with('success', 'Author berhasil dihapus.');
    }


}
