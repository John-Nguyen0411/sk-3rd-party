@extends('layouts.app')

@section('content')
    <div class="content container-fluid">
        <div class="card">
            <div class="card-body">
                <request-detail  :request_id="{{ json_encode($request_id) }}"></request-detail>
            </div>
        </div>
    </div>

@endsection