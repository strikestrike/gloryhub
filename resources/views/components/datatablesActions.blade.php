{{--@can($viewGate)--}}
@if(isset($viewGate))
<a class="btn btn-xs btn-primary" href="{{ route($crudRoutePart . '.show', $row->id) }}">
    {{ __('pages.view') }}
</a>
@endif
{{--@endcan--}}
{{--@can($editGate)--}}
@if(isset($editGate))
<a class="btn btn-xs btn-info" href="{{ route($crudRoutePart . '.edit', $row->id) }}">
    {{ __('pages.edit') }}
</a>
@endif
{{--@endcan--}}
{{--@can($deleteGate)--}}
@if(isset($deleteGate))
<form action="{{ route($crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('{{ __('pages.are_you_sure') }}');" style="display: inline-block;">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="submit" class="btn btn-xs btn-danger" value="{{ __('pages.delete') }}">
</form>
@endif
{{--@endcan--}}