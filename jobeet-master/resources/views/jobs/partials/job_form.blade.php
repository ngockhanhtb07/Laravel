<div class="form-group">
    {{ Form::label('category', 'category') }}
    {{ Form::select('category_id', $categories, null, ['class' => 'form-control']) }}
</div>
<div class="form-group">
    <!-- title -->
    {{ Form::label('title', 'Title') }}
    {{ Form::text ('title', null, ['class' => 'form-control', 'maxlength' => 255]) }}
</div>
<div class="form-group">
    <!-- Description -->
    {{ Form::label('description', 'Description') }}
    {{ Form::text ('description', null, ['class' => 'form-control']) }}
</div>