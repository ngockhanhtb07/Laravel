<div class="form-group">
    <!-- name -->
    {{ Form::label('name', 'Name') }}
    {{ Form::text('name', null, ['class' => 'form-control', 'maxlength' => 127, 'placeholder' => 'Your Name']) }}
</div>

<div class="form-group">
    <!-- email -->
    {{ Form::label('email', 'Email') }}
    {{ Form::email('email', null, ['class' => 'form-control', 'maxlength' => 511, 'placeholder' => 'Your Email']) }}
</div>

<div class="form-group">
    <!-- skills -->
    {{ Form::label('skills', 'Skills') }}
    {{ Form::text ('skills', null, ['class' => 'form-control', 'maxlength' => 1023, 'placeholder' => 'Your Skills']) }}
</div>
