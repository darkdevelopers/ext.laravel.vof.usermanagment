<div class="card">
    <div style="overflow-x: auto" class="card-body">
        @if($create)
            {!! Form::open(array('url' => route('usermanagement.store'), 'class' => 'form-signin')) !!}
            {!! Form::hidden('_method', 'POST') !!}
        @else
            {!! Form::open(array('url' => route('usermanagement.update', -1), 'class' => 'form-signin')) !!}
            {!! Form::hidden('_method', 'PUT') !!}
        @endif
        <div class="form-label-group">
            <input type="text" id="name" name="name" class="form-control @if(!empty($errors->error->first('name'))) is-invalid @endif" placeholder="@lang('vof.admin.usermanagment::usermanagment.partials.create-read-update.username')" value="{{ old('name') }}" required autofocus>
            <label for="name"></label>
        </div>
        <div class="form-label-group">
            <input type="email" id="email" name="email" class="form-control @if(!empty($errors->error->first('email')) || count($errors->error) > 0) is-invalid @endif" placeholder="@lang('vof.admin.usermanagment::usermanagment.partials.create-read-update.email')" value="{{ old('email') }}" required autofocus>
            <label for="email"></label>
        </div>
        <div class="form-label-group">
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="@lang('vof.admin.usermanagment::usermanagment.partials.create-read-update.password')" value="{{ old('password') }}" required autofocus minlength="8">
            <label for="password"></label>
        </div>
        {!! Form::button(trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.btn-create'), array('class' => 'btn btn-lg btn-primary btn-block text-uppercase','type' => 'submit')) !!}
        {!! Form::close() !!}
    </div>
</div>
