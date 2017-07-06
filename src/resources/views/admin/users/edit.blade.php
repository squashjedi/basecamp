@extends('squashjedi/basecamp::layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <squashjedi-basecamp-admin-users-edit :user="{{ json_encode($user) }}"></squashjedi-basecamp-admin-users-edit>
    </div>
</div>
@endsection