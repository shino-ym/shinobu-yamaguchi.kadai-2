@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/register.css')}}">
@endsection
@section('content')


<div class="form-container">
    <h1 class="form-title">商品登録</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="form-list">
        @csrf
        <div class="form-group">
            <label for="name" class="label">商品名<span class="required">必須</span></label>
            <input type="text" id="name" name="name" placeholder="商品名を入力" value="{{ old('name') }}" class="name-input"/>

            @if ($errors->has('name'))
            <div class="error">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div class="form-group">
            <label for="price" class="label">値段<span class="required">必須</span></label>
            <input type="text" id="price" name="price" placeholder="値段を入力" value="{{ old('price') }}" class="price-input"/>

            @if ($errors->has('price'))
                <ul class="error-list">
                    @foreach ($errors->get('price') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="form-group">
            <div class="img-container"></div>
            <label class="label">商品画像<span class="required">必須</span></label>
            <div class="product-card">
                <img id="preview" src="{{ old('preview_image', $product->img ?? '') }}" alt="" class="preview-image" style="display:none; ">
            </div>
            <button type="button" onclick="document.getElementById('image').click()" class="img-btn">
                ファイルを選択
            </button>

            <span id="fileName" class="file-name"></span>

            <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)" hidden>

            @if ($errors->has('image'))
            <ul class="error-list">
                @foreach ($errors->get('image') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="form-group">
            <label class="label">季節 <span class="required">必須</span> <span class="note">複数選択可</span></label>
                <div class="checkbox-group">
                    @foreach($seasons as $season)
                        <label class="circle-checkbox">
                            <input type="checkbox" name="season_id[]" value="{{ $season->id }}"
                                @if(collect(old('season_id', []))->contains($season->id)) checked @endif>
                            <span class="checkmark"></span>
                            {{ $season->name }}
                        </label>
                    @endforeach
                    @if ($errors->has('season_id'))
                        <div class="error">{{ $errors->first('season_id') }}</div>
                    @endif
                </div>
        </div>

        <div class="form-group">
            <label for="description" class="label">商品説明<span class="required">必須</span></label>
                <textarea name="description" rows="5" placeholder="商品の説明を入力">{{ old('description') }}</textarea>

                @if ($errors->has('description'))
                    <ul class="error-list">
                        @foreach ($errors->get('description') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
        </div>
        <div class="center-buttons">
            <a href="{{ route('products.index') }}" class="back-btn">戻る</a>
            <button type="submit" class="submit-btn">登録</button>
        </div>
    </form>
</div>

@endsection

@section('script')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = () => {
        const preview = document.getElementById('preview');
        preview.src = reader.result;
        preview.style.display = 'block';
        document.getElementById('fileName').textContent = file.name;
    };
    reader.readAsDataURL(file);
}

</script>
@endsection