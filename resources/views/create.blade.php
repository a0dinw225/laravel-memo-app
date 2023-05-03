@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">新規メモ作成</div>
        <form class="card-body my-card-body" action="{{ route('store') }}" method="POST">
            @csrf
            @error('message')
                <div class="alert alert-danger mt-3">
                    {{ $message }}
                </div>
            @enderror
            <div class="form-group">
                <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力"></textarea>
            </div>
            @error('content')
                <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror
            @foreach($tags as $tag)
                <div class="form-check form-check-inline mb-3">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}">
                    <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name'] }}</label>
                </div>
            @endforeach
            <div class="form-group mt-3">
                <input type="text" class="form-control w-50" name="new_tag" placeholder="新しいタグを入力">
            </div>
            <button type="submit" class="btn btn-primary mt-3">保存</button>
        </form>
    </div>
@endsection