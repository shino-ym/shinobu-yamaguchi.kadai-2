@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/edit.css')}}">
@endsection

@section('content')

<div class="edit-container">
    <div class="edit-form">
        <form action="{{ route('products.update', ['productId' => $product->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <section class="product-info">
                <a href="{{ route('products.index') }}" class="product-link">商品一覧</a>
                &gt;
                <span class="current">{{ $product->name }}</span>
            </section>

            <section class="product-card">
                <div class="product-pic">
                    <img src="{{ asset('storage/' . $product->img) }}" alt="{{ $product->name }}" class="img-card">
                    <div class="file-input-wrapper">
                        <button type="button" onclick="document.getElementById('image').click()" class="img-btn">
                            ファイルを選択
                        </button>

                        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)" hidden>
                        @if($product->img)
                            <span id="fileName" class="file-name">{{ basename($product->img) }}</span>
                        @endif
                    </div>
                    @if ($errors->has('image'))
                    <ul class="error-list">
                        @foreach ($errors->get('image') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                <div class="product-summary">
                    <div class="form-group">
                        <label class="section-label">商品名</label>
                            <input type="text" name="name" placeholder="商品名を入力" value="{{ old('name', $product->name) }}" class="name-input">
                            @if ($errors->has('name'))
                            <div class="error">{{ $errors->first('name') }}</div>
                            @endif
                    </div>

                    <div class="form-group">
                        <label class="section-label">値段</label>
                        <input type="text" name="price" placeholder="値段を入力" value="{{ old('price', $product->price) }}" class="price-input">
                        @if ($errors->has('price'))
                            <ul class="error-list">
                                @foreach ($errors->get('price') as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="section-label">季節</label>
                        <div class="circle-checkbox">
                            @foreach($seasons as $season)
                                <label>
                                    <input type="checkbox" name="season_id[]" value="{{ $season->id }}" @if(
                                        is_array(old('season_id'))
                                            ? in_array($season->id, old('season_id'))
                                            : $product->seasons->contains($season->id)
                                    ) checked @endif>
                                    <span class="checkmark"></span>
                                        {{ $season->name }}
                                </label>
                            @endforeach
                            @if ($errors->has('season_id'))
                                <div class="error">{{ $errors->first('season_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <section class="product-description">
                <div class="form-group">
                    <label class="section-label">商品説明</label>
                    <textarea name="description" rows="5" placeholder="商品の説明を入力">{{ old('description', $product->description) }}</textarea>

                    @if ($errors->has('description'))
                        <ul class="error-list">
                            @foreach ($errors->get('description') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>

            <section class="center-buttons">
                <a href="{{ route('products.index') }}" class="back-btn">戻る</a>
                <button type="submit" class="submit-btn">変更を保存</button>
            </section>
        </form>
    </div>
    <div class="delete-wrapper">
        <form action="{{ route('products.destroy', ['productId' => $product->id]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="delete-form">
            @csrf
            @method('DELETE')
                <button type="submit" class="delete-btn">
                    <img src="{{ asset('images/icons/TiTrash.png') }}" alt="削除">
                </button>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = () => {
        document.querySelector('.product-pic img').src = reader.result;
    };
    if (file) {
        reader.readAsDataURL(file);
        document.getElementById('fileName').textContent = file.name;
    }
}</script>
@endsection

