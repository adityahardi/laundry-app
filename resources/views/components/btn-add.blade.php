@props(['nama'])
<a {{ $attributes->merge(['class' => 'btn btn-primary']) }}>
    <i class="fas fa-plus mr-2"></i> Add <?= $nama ?>
</a>
