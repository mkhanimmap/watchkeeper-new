@if ($errors->has())
    <div class="alert alert-danger">
        <ul>
            @each('shared.errors-li', $errors->all(), 'error', 'shared.errors.empty')
        </ul>
    </div>
@endif
