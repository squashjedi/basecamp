@extends('squashjedi/basecamp::layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <squashjedi-basecamp-admin-users-create></squashjedi-basecamp-admin-users-create>
    </div>
</div>
@endsection

@section('scripts')
    @include('squashjedi/basecamp::partials.clean-facebook-url')
@endsection