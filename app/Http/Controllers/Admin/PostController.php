<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class PostController extends Controller
{

    // Tüm blog yazılarını listeler
    public function index(Request $request)
{
    $pageSize = 10;
    $page = $request->get('page', 1);
    $categoryId = $request->get('category');

    if ($categoryId) {
        $query = Post::where('categoryId', $categoryId); // Burada "categoryId" doğru alan adıdır
        $posts = $query->skip(($page - 1) * $pageSize)
            ->take($pageSize)
            ->get();

        return response()->json([
            'data' => $posts,
            'current_page' => $page,
            'per_page' => $pageSize,
            'total' => $query->count(),
        ]);
    }

    $query = Post::orderBy('created_at', 'desc');
    $posts = $query->skip(($page - 1) * $pageSize)
        ->take($pageSize)
        ->get();

    return response()->json([
        'data' => $posts,
        'current_page' => $page,
        'per_page' => $pageSize,
        'total' => $query->count(),
    ]);
}

    
 public function allPosts(Request $request)
    {
        return  response()->json(Post::all());
    }

    // Belirli bir blog yazısını gösterir
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    // Yeni bir blog yazısı oluşturmak için
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'description' => 'required',
            'categoryId' => 'required',

        ]);

        $post = Post::create($validatedData);

        return response()->json($post, 201);
    }

    // Bir blog yazısını günceller
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'description' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($validatedData);

        return response()->json($post);
    }

    // Bir blog yazısını siler
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json('Post basariyla silindi.');
    }
    public function search(Request $request)
    {
        $searchTerm = $request->input('term'); // Arama terimini al
    
        // Arama işlemini gerçekleştirerek filtrelenmiş gönderileri 10'ar 10'ar sayfala
        $filteredPosts = Post::where('title', 'LIKE', '%' . $searchTerm . '%')->paginate(10);
    
        return response()->json($filteredPosts); // Filtrelenmiş gönderileri JSON formatında döndür
    }
    
 


// public function categories(Request $request)
// {
//     $query = Post::query();

//     // Kategori parametresi varsa, filtrelemeyi uygula
//     if ($request->has('category')) {
//         $category = $request->query('category');
//         $query->where('category', $category);
//     }

//     $perPage = 10; // Her sayfada gösterilecek gönderi sayısı
//     $posts = $query->paginate($perPage);

//     return response()->json($posts);
// }
public function createCategory(Request $request)
{
    $name = $request->input('name');
    $category = Category::where('name', $name)->first();

    if (!$category) {
        // İlgili kategori bulunamadıysa yeni bir kategori oluştur
        $category = Category::create(['name' => $name]);
        
        if ($category) {
            return response()->json('Kategori başarıyla oluşturuldu');
        }
    }
    
    return response()->json('Kategori oluşturma hatası');
}


    public function getCategories(Request $request)
    {
        return  response()->json(Category::all());
    }

    
    public function assignCategory(Request $request, $postId)
    {
        $categoryId = $request->input('category_id');
        
        try {
            $category = Category::findOrFail($categoryId);
            $post = Post::findOrFail($postId);
            
            // Gönderinin kategori ID'sini güncelle
            $post->category_id = $category->id;
            $post->save();
            
            return response()->json('Post assigned to category');
        } catch (\Exception $e) {
            return response()->json('Category assignment failed');
        }
    }
    


}
