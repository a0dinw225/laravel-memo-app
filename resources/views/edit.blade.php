@extends('layouts.app')

@section('javascript')
<script src="/js/confirm.js"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            メモ編集
            <form id="delete-form" action="{{ route('delete') }}" method="POST">
                @csrf
                <input type="hidden" name="memo_id" value="{{ $memo['id'] }}" />
                <i class="fas fa-trash me-3" onclick="deleteHandle(event);"></i>
            </form>
        </div>
        <form class="card-body my-card-body" action="{{ route('update') }}" method="POST">
            @csrf
            @error('message')
                <div class="alert alert-danger mt-3">
                    {{ $message }}
                </div>
            @enderror
            <input type="hidden" name="memo_id" value="{{ $memo['id'] }}" />
            <div class="form-group mb-3">
                <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力">{{ $memo['content'] }}</textarea>
            </div>
            @error('content')
                <div class="alert alert-danger mt-3">メモ内容を入力してください!</div>
            @enderror
            @foreach($tags as $tag)
                <div class="form-check form-check-inline mb-3">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="{{ $tag['id'] }}" value="{{ $tag['id'] }}" {{ in_array($tag['id'], $memoTagIds) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $tag['id'] }}">{{ $tag['name'] }}</label>
                </div>
            @endforeach
            <div class="form-group mb-3">
                <input type="text" class="form-control w-50" name="new_tag" placeholder="新しいタグを入力" />
            </div>
            @error('new_tag')
                <div class="alert alert-danger mt-3">{{ $message }}</div>
            @enderror
            <button type="submit" class="btn btn-primary">更新</button>
        </form>
    </div>
@endsection