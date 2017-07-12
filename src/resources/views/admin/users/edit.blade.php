@extends('squashjedi/basecamp::layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <basecamp-admin-users-edit :user="{{ json_encode($user) }}"></basecamp-admin-users-edit>
    </div>
</div>
@endsection