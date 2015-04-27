@extends('app')
@section('content')
<div class="page-header">
    <h3><strong>User <span style="color:brown;">{{ $user->fio}}</span> kved list:</strong></h3>
</div>
<div class="row">
    <div class="col-md-5">
        @include('partials.flash')
        @include('partials.errors')
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/kved/kved') }}">
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <div class="col-md-6">
                    <input type="kved" class="form-control" name="kved" value="{{ old('kved') }}" style='margin-left:0px;'>
                </div>
                <div class="col-md-2" style='float:left; display: inline;'>
                    <button type="submit" class="btn btn-primary" style='margin-left:0px;'>Add Kved</button>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Main</th>
                    <th>Description</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ordered_kveds as $kved)
                <tr data-id="{{ $kved->id }}">
                    <td>{{ $kved->kved }}</td>
                    <td><input type="checkbox" name="main" onchange="kved.set_main(this);" @if($kved->pivot->main) checked="checked" @endif></td>
                    <td>{{ $kved->description }}</td>
                    <td>
                        <a href="javascript:void(0);" title="Edit"><span aria-hidden="true" onclick="kved.edit_description(this);" class="glyphicon glyphicon-edit"></span></a>
                        <a href="javascript:void(0);" title="Delete"><span aria-hidden="true" onclick="kved.delete_user_kved(this);" class="glyphicon glyphicon-remove"></span></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: left;"><strong>No kved available for you. Add them now!</strong></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('footer')
<script src="{{ url('/js/kved.js') }}"></script>
@endsection