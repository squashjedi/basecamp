@extends('squashjedi/basecamp::layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <basecamp-admin-users :routeCreate="{{ json_encode(route('users.create')) }}"></basecamp-admin-users>
    </div>
</div>
@endsection