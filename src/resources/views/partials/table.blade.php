<div class="card">
    <a href="{{ route('usermanagement.create') }}" class="btn btn-primary">
        <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
        @lang('vof.admin.usermanagment::usermanagment.partials.table.create-new-user-btn')
    </a>
    <div style="overflow-x: auto" class="card-body">
        {{ $admins->links() }}
        <table width="100%" class="table table-striped task-table">
            <thead>
            <tr>
                <th>@lang('vof.admin.usermanagment::usermanagment.partials.table.username')</th>
                <th>@lang('vof.admin.usermanagment::usermanagment.partials.table.email')</th>
                <th>@lang('vof.admin.usermanagment::usermanagment.partials.table.created')</th>
                <th>@lang('vof.admin.usermanagment::usermanagment.partials.table.updated')</th>
                <th>@lang('vof.admin.usermanagment::usermanagment.partials.table.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td class="table-text">{{ $admin->name }}</td>
                    <td class="table-text">{{ $admin->email }}</td>
                    <td class="table-text">{{ $admin->created_at }}</td>
                    <td class="table-text">{{ $admin->updated_at }}</td>

                    <td class="table-btn">
                        {!! Form::open(array('url' => route('usermanagement.destroy', $admin->id), 'class' => '', 'data-toggle' => 'tooltip', 'title' => trans('vof.admin.usermanagment::usermanagment.partials.table.delete_tooltip'))) !!}
                        {!! Form::hidden('_method', 'DELETE') !!}
                        {!! Form::button(trans('vof.admin.usermanagment::usermanagment.partials.table.delete_button'), array('class' => 'btn btn-danger','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => trans('vof.admin.usermanagment::usermanagment.partials.table.delete_user_title'), 'data-message' => trans('vof.admin.usermanagment::usermanagment.partials.table.delete_user_message', ['admin' => $admin->name]))) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $admins->links() }}
    </div>
</div>
@include('vof.admin.usermanagment::modal.delete', ['name' => 'Admin'])
