@extends('layouts.master')
@section('content')
<h3>List of Classifications</h3>
<h5> {{ link_to_route('admin.classifications.create','Create',null,  array('id'=> 'create-permission' , 'class' => 'btn  btn-xs btn-primary')) }}</h5>
<div class="row">
    <div class="col-sm-12">
        <select id="classification_group" name="classification_group" class="form-control chosen-select">
                <option value="0">-- All Classification Types -- </option>
            @foreach($classificationTypes as $classificationTypeKey => $classificationTypeValue)
                <option value="{{ $classificationTypeKey }}" {{ (
            isset($categoryId) && $categoryId == $classificationTypeKey)
            ? "selected" : '' }}> {{ ucwords(strtolower($classificationTypeValue)) }}</option>
            @endforeach
        </select>
        @if ($classifications->count() > 0)
            {{ pagination_links($classifications)  }}
            <table class="table table-bordered table-hover table-condensed">
                <thead>
                    <tr>
                        <th>{{ sort_by('code','Code','admin.classifications.index') }}</th>
                        <th>{{ sort_by('name','Name','admin.classifications.index') }}</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classifications as $classification)
                        <tr>
                            <td> {{{ $classification->code }}}</td>
                            <td> {{{ $classification->name }}} </td>
                            <td> {{ucwords(strtolower($classificationTypes["$classification->group_id"])) }} </td>
                            <td>{{ link_to_route('admin.classifications.edit','Edit',array( 'id' => $classification->id ), array('class' => 'btn btn-xs btn-success')) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ pagination_links($classifications)  }}
        @else
            <p>No Records !!</p>
        @endif
    </div>
</div>
@stop
@section('script.footer')
    @include('shared.chosen-jquery')
    @include('shared.classification-index-script')
@stop

