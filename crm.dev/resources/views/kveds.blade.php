@extends('app')
@section('content')
<div class="page-header">
    <h3><strong>CRM</strong></h3>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @if($kveds)
                @forelse($kveds as $kved)
                <tr data-id="{{ $kved->id }}">
                    <td style="width:10%;">{{ $kved->kved }}</td>
                    <td style="width:80%;">{{ $kved->description }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: left;"><strong>No kved are available</strong></td>
                </tr>
                @endforelse
               @endif
            </tbody>
        </table>
    </div>
</div>
@endsection