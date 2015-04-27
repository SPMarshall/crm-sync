@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><strong style="font-size:15pt;">Register</strong></div>
                <div class="panel-body">
                    @include('partials.errors')
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address: <span aria-hidden="true" class="glyphicon-asterisk"></span></label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Full Name: <span aria-hidden="true" class="glyphicon-asterisk"></span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="fio" value="{{ old('fio') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">INN: <span aria-hidden="true" class="glyphicon-asterisk"></span></label>
                            <div class="col-md-6">
                                <input type="number" class="form-control" name="inn" value="{{ old('inn') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Company: <span aria-hidden="true" class="glyphicon-asterisk"></span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="company_name" value="{{ old('inn') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
    < script >
		@endsection
