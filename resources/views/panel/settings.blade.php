@extends('layouts.app-panel')

@section('content')
    <h3 class="text-center pt-3">Site Settings</h3>
    <hr>
    {{ Form::open(['url' => route('panel-updateSettings'), 'class' => 'pt-3 mt-auto']) }}
        <div class="form-group">
            <div class="row">
                <div class="col">
                    {{ Form::label('siteName', 'Site Name') }}
                    {{ Form::text('siteName', null, ['class' => 'form-control', 'placeholder' => $siteName]) }}
                </div>
                <div class="col">
                    {{ Form::label('paypal', 'Paypal Link') }}
                    {{ Form::url('paypal', null, ['class' => 'form-control', 'placeholder' => $paypal]) }}
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col">
                    {{ Form::label('rDelay', 'DJ Request Delay') }}
                    {{ Form::text('rDelay', null, ['class' => 'form-control', 'placeholder' => $requestDelay]) }}
                </div>
                <div class="col">
                    {{ Form::label('rADjDelay', 'AutoDJ Request Delay') }}
                    {{ Form::url('rADjDelay', null, ['class' => 'form-control', 'placeholder' => $requestAutoDJDelay]) }}
                </div>
            </div>
        </div>
        <div class="text-right">
            {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
        </div>
    {{ Form::close() }}
    <hr>
    <div class="row">
        <div class="col">
            <div class="card settings-card h-100">
                <div class="card-header">Rules</div>
                <div class="card-body d-flex flex-column">
                    <ul class="list-group list-group-flush scroll">
                        @forelse ($rules as $rule)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">
                                    {{$rule['rule']}}
                                </div>
                                <div class="col ml-auto">
                                    <button type="button" class="btn btn-danger btn-sm ml-auto">Delete</button>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item">No Rules</li>
                        @endforelse
                    </ul>
                    {{ Form::open(['url' => route('panel-addRule'), 'class' => 'pt-3 mt-auto']) }}
                        <div class="form-group">
                            {{ Form::label('rule', 'Add Rule') }}
                            {{ Form::text('rule', null, ['class' => 'form-control', 'placeholder' => 'New Rule']) }}
                        </div>
                        <div class="text-right">
                            {{ Form::submit('Add', ['class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card settings-card h-100">
                <div class="card-header">Banned Songs</div>
                <div class="card-body d-flex flex-column">
                    <ul class="list-group list-group-flush scroll">
                        @forelse ($bsongs as $song)
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col">
                                    {{$song['bsong']}}
                                </div>
                                <div class="col ml-auto">
                                    <button type="button" class="btn btn-danger btn-sm ml-auto">Delete</button>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item">No Banned Songs</li>
                        @endforelse
                    </ul>
                    {{ Form::open(['url' => route('panel-addBSong'), 'class' => 'pt-3 mt-auto']) }}
                        <div class="form-group">
                            {{ Form::label('addSong', 'Add Song') }}
                            {{ Form::text('addSong', null, ['class' => 'form-control', 'placeholder' => 'Song']) }}
                        </div>
                        <div class="text-right">
                            {{ Form::submit('Add', ['class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card settings-card h-100">
                <div class="card-header">Info</div>
                <div class="card-body">
                    <form class="h-100">
                        <div class="h-100 pb-5">
                            <div class="form-group h-100 pb-5 m-0">
                                <label for="info">Change Info</label>
                                <textarea class="form-control h-100" id="info" rows="4">{{$info}}</textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
