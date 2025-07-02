<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Proyek</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $project->name ?? '') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $project->description ?? '') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
         <div class="mb-3">
            <label for="team_members" class="form-label">Anggota Tim</label>
            <select class="form-select" name="team_members[]" id="team_members" multiple>
                 @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ in_array($employee->id, old('team_members', $team_member_ids ?? [])) ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
            <small class="form-text">Tahan Ctrl (atau Cmd di Mac) untuk memilih lebih dari satu.</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', isset($project) ? \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') : '') }}" required>
            @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="deadline_date" class="form-label">Tanggal Deadline</label>
            <input type="date" class="form-control @error('deadline_date') is-invalid @enderror" id="deadline_date" name="deadline_date" value="{{ old('deadline_date', isset($project) ? \Carbon\Carbon::parse($project->deadline_date)->format('Y-m-d') : '') }}" required>
            @error('deadline_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                @foreach(['Belum Dimulai', 'In Progress', 'Selesai', 'Revisi', 'Dibatalkan'] as $status)
                    <option value="{{ $status }}" {{ old('status', $project->status ?? '') == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
             @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Prioritas</label>
            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                 @foreach(['Rendah', 'Sedang', 'Tinggi'] as $priority)
                    <option value="{{ $priority }}" {{ old('priority', $project->priority ?? '') == $priority ? 'selected' : '' }}>{{ $priority }}</option>
                @endforeach
            </select>
            @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn-gradient btn-gradient-blue">Simpan Proyek</button>
    <a href="{{ route('pm.projects.index') }}" class="btn-gradient btn-gradient-gray">Batal</a>
</div>