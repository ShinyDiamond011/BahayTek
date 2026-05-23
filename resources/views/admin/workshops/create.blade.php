@extends('layouts.admin')
@section('title','Create Workshop')

@section('content')
<div class="page-header">
  <div style="display:flex;align-items:center;gap:12px">
    <a href="{{ route('admin.workshops.index') }}" style="display:flex;align-items:center;color:var(--gray);text-decoration:none;font-size:.82rem;font-weight:600">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Workshops
    </a>
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    <h1 class="page-title" style="margin:0;font-size:1.1rem">Create Workshop</h1>
  </div>
</div>

<div style="max-width:680px">
  <div class="card">
    <form method="POST" action="{{ route('admin.workshops.store') }}" style="padding:24px">
      @csrf

      <div style="margin-bottom:16px">
        <label style="font-size:.75rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Workshop Title *</label>
        <input class="form-control @error('title') error @enderror" type="text" name="title" value="{{ old('title') }}" required placeholder="e.g., Solar Panel Installation Basics">
        @error('title')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:16px">
        <label style="font-size:.75rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Description</label>
        <textarea class="form-control" name="description" rows="4" placeholder="Topics covered, requirements, what participants will learn...">{{ old('description') }}</textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
        <div>
          <label style="font-size:.75rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Date &amp; Time *</label>
          <input class="form-control @error('session_datetime') error @enderror" type="datetime-local" name="session_datetime" value="{{ old('session_datetime') }}" required>
          @error('session_datetime')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
        </div>
        <div>
          <label style="font-size:.75rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Max Participants *</label>
          <input class="form-control @error('max_participants') error @enderror" type="number" name="max_participants" value="{{ old('max_participants', 20) }}" min="1" required>
          @error('max_participants')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="margin-bottom:16px">
        <label style="font-size:.75rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Registration Deadline <span style="font-weight:400;color:var(--gray)">(optional)</span></label>
        <input class="form-control @error('registration_deadline') error @enderror" type="datetime-local" name="registration_deadline" value="{{ old('registration_deadline') }}">
        <div style="font-size:.68rem;color:var(--gray);margin-top:3px">After this date/time, users will no longer be able to register. Leave blank for no deadline.</div>
        @error('registration_deadline')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
      </div>

      <div style="margin-bottom:16px">
        <label style="font-size:.75rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Venue *</label>
        <input class="form-control @error('venue') error @enderror" type="text" name="venue" value="{{ old('venue') }}" required placeholder="e.g., BAHAYTEK Training Center, Legazpi City">
        @error('venue')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
        <div>
          <label style="font-size:.75rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Registration Fee (₱) *</label>
          <input class="form-control @error('fee') error @enderror" type="number" name="fee" value="{{ old('fee', 0) }}" min="0" step="0.01" required>
          <div style="font-size:.68rem;color:var(--gray);margin-top:3px">Enter 0 for free sessions</div>
          @error('fee')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
        </div>
        <div>
          <label style="font-size:.75rem;font-weight:700;color:var(--dark);display:block;margin-bottom:5px">Status *</label>
          <select class="form-control form-select @error('status') error @enderror" name="status">
            <option value="open" {{ old('status','open')==='open'?'selected':'' }}>Open</option>
            <option value="coming_soon" {{ old('status')==='coming_soon'?'selected':'' }}>Coming Soon</option>
            <option value="ongoing" {{ old('status')==='ongoing'?'selected':'' }}>Ongoing</option>
            <option value="completed" {{ old('status')==='completed'?'selected':'' }}>Completed</option>
            <option value="cancelled" {{ old('status')==='cancelled'?'selected':'' }}>Cancelled</option>
          </select>
          @error('status')<div style="font-size:.7rem;color:#dc2626;margin-top:3px">{{ $message }}</div>@enderror
        </div>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;border-top:1px solid var(--border);padding-top:18px">
        <a href="{{ route('admin.workshops.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Workshop</button>
      </div>
    </form>
  </div>
</div>
@endsection
