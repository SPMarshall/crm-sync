@extends('app')
@section('content')
<div class="page-header">
    <h3><strong>Kved List:</strong></h3>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kveds as $kved)
                <tr data-id="{{ $kved->id }}">
                    <td style="width:10%;">{{ $kved->kved }}</td>
                    <td style="width:80%;">{{ $kved->description }}</td>
                    <td style="width:10%;">
                       <a href="javascript:void(0);" title="Delete"><span aria-hidden="true" onclick="kved.delete(this);" class="glyphicon glyphicon-remove" ></span></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: left;"><strong>No kved are available</strong></td>
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