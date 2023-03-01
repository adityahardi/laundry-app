@props(['name'])
<form action="get" class="ml-auto">
    <div class="input-group">
        <input name="{{ $name }}" value="<?= request()->input($name) ?>" type="text" class="form-control"
        placeholder="Search...">
        <div class="input-group-append">
            <button type="submit" class="btn btn-secondary">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</form>
