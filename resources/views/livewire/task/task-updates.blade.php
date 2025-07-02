<div>
    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="submitUpdate">
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi Progress</label>
            <textarea wire:model="description" class="form-control" rows="4"></textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="mb-3">
            <label for="hours_spent" class="form-label">Jam Kerja (Opsional)</label>
            <input type="number" step="0.5" wire:model="hours_spent" class="form-control">
            @error('hours_spent') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
    </form>

    <hr class="my-4">

    <h4>Riwayat Progress</h4>
    @forelse($updates as $update)
        <div class="card mb-2">
            <div class="card-body">
                <p>{{ $update->description }}</p>
                <small class="text-muted">
                    Dilaporkan oleh {{ $update->user->name }} pada {{ $update->created_at->format('d M Y, H:i') }}
                    @if($update->hours_spent)
                        - {{ $update->hours_spent }} jam
                    @endif
                </small>
            </div>
        </div>
    @empty
        <p>Belum ada progress yang dilaporkan.</p>
    @endforelse
</div>