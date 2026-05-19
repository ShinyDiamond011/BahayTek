@extends('layouts.admin')
@section('title','Users')

@section('content')
<div class="page-header">
  <div>
    <h1 class="page-title">Users</h1>
    <p class="page-sub">Manage registered user accounts.</p>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card" style="margin-bottom:18px">
  <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;padding:16px 20px">
    <div style="flex:1;min-width:200px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Search</div>
      <input class="form-control" type="text" name="search" placeholder="Name or email..." value="{{ request('search') }}">
    </div>
    <div style="min-width:120px">
      <div style="font-size:.73rem;font-weight:700;color:var(--dark);margin-bottom:5px">Status</div>
      <select class="form-control form-select" name="status">
        <option value="">All</option>
        <option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option>
        <option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option>
        <option value="suspended" {{ request('status')==='suspended'?'selected':'' }}>Suspended</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary" style="height:38px">Filter</button>
    @if(request()->hasAny(['search','status']))
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="height:38px">Clear</a>
    @endif
  </form>
</div>

<div class="card">
  <div class="tbl-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>User</th>
          <th>Contact</th>
          <th>Orders</th>
          <th>Bookings</th>
          <th>Status</th>
          <th>Joined</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        @php
          $sc = match($user->status){
            'inactive'  => 'badge-secondary',
            'suspended' => 'badge-danger',
            default     => 'badge-success',
          };
        @endphp
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:34px;height:34px;border-radius:50%;background:var(--sand);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.82rem;font-weight:700;color:var(--forest)">
                {{ strtoupper(substr($user->first_name,0,1).substr($user->last_name,0,1)) }}
              </div>
              <div>
                <div style="font-size:.82rem;font-weight:700;color:var(--dark)">{{ $user->first_name }} {{ $user->last_name }}</div>
                <div style="font-size:.7rem;color:var(--gray)">#{{ $user->id }}</div>
              </div>
            </div>
          </td>
          <td>
            <div style="font-size:.78rem;color:var(--dark)">{{ $user->email }}</div>
            <div style="font-size:.72rem;color:var(--gray)">{{ $user->phone }}</div>
          </td>
          <td style="font-size:.82rem;font-weight:600;color:var(--dark)">{{ $user->orders_count }}</td>
          <td style="font-size:.82rem;font-weight:600;color:var(--dark)">{{ $user->booking_schedules_count }}</td>
          <td><span class="badge {{ $sc }}">{{ ucfirst($user->status) }}</span></td>
          <td style="font-size:.75rem;color:var(--gray)">{{ $user->created_at->format('M j, Y') }}</td>
          <td>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">View</a>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--gray)">No users found.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
  <div style="padding:16px 20px;border-top:1px solid var(--border)">{{ $users->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
