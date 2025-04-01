<form method="POST" action="{{ route('game-data.store') }}">
    @csrf
    <div class="form-group">
        <label>Castle Level</label>
        <input type="number" name="castle_level" min="45" max="50" class="form-control" required>
    </div>
    <!-- Repeat for other fields -->
</form>