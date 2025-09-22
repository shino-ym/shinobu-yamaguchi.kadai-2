<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Season;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\StoreProductRequest;


class ProductController extends Controller
{
    // 商品一覧
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $sort = $request->query('sort');

        $query = Product::query();

        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($sort === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('id', 'asc');
        }

        $products = $query->paginate(6)->withQueryString();

        return view('products.index', compact('products', 'keyword', 'sort'));
    }

    // 商品登録フォーム
    public function create()
    {
        $seasons = Season::all();
        return view('products.register',compact('seasons'));
    }

    // 新規登録（db保存）
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // 画像保存
        if ($request->hasFile('image')&& $request->file('image')->isValid()) {
            $path = $request->file('image')->store('img', 'public');
            $data['img'] = $path;
        }

        $product = Product::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'img' => $data['img'],
            'description' => $data['description'],
        ]);

        // 季節との中間テーブルを保存
        $product->seasons()->sync($request->season_id ?? []);

        return redirect()->route('products.index');

    }

    // 商品検索
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $sort = $request->input('sort'); // URLではなく 'asc' / 'desc' が入る想定

        $query = Product::query();

        // キーワード検索
        if (!empty($keyword)) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        // 並び替え
        if ($sort === 'asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('id', 'asc'); // デフォルト
        }

        $products = $query->paginate(6)->withQueryString();

        return view('products.index', compact('products', 'keyword'));
    }

    // 編集
    public function edit($productId)
    {
        // IDから商品を取得
        $product = Product::find($productId);

        $seasons = Season::all();
        return view('products.edit', compact('product','seasons'));
    }

    // 商品更新
    public function update(UpdateProductRequest $request,$productId)
    {
        // IDから商品を取得
        $product = Product::find($productId);

        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');

        // 画像が選択されていれば保存
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('img', 'public'); // storage/app/public/img に保存
            $product->img = $path; // DB に保存するのは相対パス
        }

        $product->save();

        // 多対多の季節を更新
        $product->seasons()->sync($request->season_id ?? []);

        return redirect()->route('products.index');
    }

    // 消去
    public function destroy($productId)
    {
        $product = Product::find($productId);

        // 画像も削除したい場合は storage から削除
        if ($product->img) {
            \Storage::disk('public')->delete($product->img);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました');

    }
}
