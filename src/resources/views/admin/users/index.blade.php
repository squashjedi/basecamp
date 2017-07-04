@extends('squashjedi/basecamp::layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <squashjedi-basecamp-admin-users :routeCreate="{{ json_encode(route('users.create')) }}"></squashjedi-basecamp-admin-users>
    </div>
</div>
@endsection

@section('scripts')
    @include('squashjedi/basecamp::partials.clean-facebook-url')
@endsection