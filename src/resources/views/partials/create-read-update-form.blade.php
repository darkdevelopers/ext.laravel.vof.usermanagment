<div class="card">
    <div style="overflow-x: auto" class="card-body">
        @if($create)
            {!! Form::open(array('url' => route('usermanagement.store'), 'class' => 'form-signin')) !!}
            {!! Form::hidden('_method', 'POST') !!}
        @else
            {!! Form::open(array('url' => route('usermanagement.update', $admin->id ), 'class' => 'form-signin')) !!}
            {!! Form::hidden('_method', 'PUT') !!}
        @endif
        <div class="form-label-group">
            <input type="text" id="name" name="name" class="form-control @if(!empty($errors->error->first('name'))) is-invalid @endif" placeholder="@lang('vof.admin.usermanagment::usermanagment.partials.create-read-update.username')" value="@if(empty($admin)){{ old('name') }}@else {{ $admin->name }}@endif" required autofocus @if($disabled)disabled="disabled"@endif>
            <label for="name"></label>
        </div>
        <div class="form-label-group">
            <input type="email" id="email" name="email" class="form-control @if(!empty($errors->error->first('email')) || count($errors->error) > 0) is-invalid @endif" placeholder="@lang('vof.admin.usermanagment::usermanagment.partials.create-read-update.email')" value="@if(empty($admin)){{ old('email') }}@else {{ $admin->email }}@endif" required autofocus @if($disabled)disabled="disabled"@endif>
            <label for="email"></label>
        </div>
        <div class="form-label-group">
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="@lang('vof.admin.usermanagment::usermanagment.partials.create-read-update.password')" value="{{ old('password') }}" required autofocus minlength="8" @if($disabled)disabled="disabled"@endif>
            <label for="password"></label>
        </div>
        {!! Form::button(trans('vof.admin.usermanagment::usermanagment.partials.create-read-update.btn-save'), array('class' => 'btn btn-lg btn-primary btn-block text-uppercase','type' => 'submit')) !!}
        {!! Form::close() !!}
    </div>
</div>
