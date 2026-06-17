@php
    $defaultRole = setting('user_defaults', 'default_role');
    $selectedRoles = old('roles', isset($user)
        ? $user->roles->pluck('name')->all()
        : ($defaultRole ? [$defaultRole] : []));
    $defaultStatus = setting('user_defaults', 'default_status', 'active');
    $inputClass = 'w-full rounded-lg border border-outline-variant bg-white px-md py-2 outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20';
@endphp

<div class="grid grid-cols-1 gap-md md:grid-cols-2">
    <div class="space-y-1">
        <label class="text-label-sm font-bold text-on-surface-variant">Full Name <span class="text-error">*</span></label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" maxlength="255" required class="{{ $inputClass }}"/>
    </div>
    <div class="space-y-1">
        <label class="text-label-sm font-bold text-on-surface-variant">Username</label>
        <input type="text" name="username" value="{{ old('username', $user->username ?? '') }}" maxlength="255" class="{{ $inputClass }}"/>
    </div>
    <div class="space-y-1">
        <label class="text-label-sm font-bold text-on-surface-variant">Email <span class="text-error">*</span></label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" maxlength="255" required class="{{ $inputClass }}"/>
    </div>
    <div class="space-y-1">
        <label class="text-label-sm font-bold text-on-surface-variant">Phone</label>
        <input type="text" name="phone" data-mask="phone" value="{{ old('phone', $user->phone ?? '') }}" maxlength="12" placeholder="0300-0000000" class="{{ $inputClass }}"/>
    </div>
    <div class="space-y-1">
        <label class="text-label-sm font-bold text-on-surface-variant">Status <span class="text-error">*</span></label>
        <select name="status" required class="{{ $inputClass }}">
            @foreach (['active', 'inactive', 'suspended', 'pending'] as $st)
                <option value="{{ $st }}" @selected(old('status', $user->status ?? $defaultStatus) === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </div>
    <div class="space-y-1">
        <label class="text-label-sm font-bold text-on-surface-variant">Roles</label>
        <select name="roles[]" multiple data-select2-parent placeholder="Assign roles..." class="{{ $inputClass }}">
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(in_array($role, $selectedRoles))>{{ Str::headline($role) }}</option>
            @endforeach
        </select>
    </div>
    <div class="space-y-1">
        <label class="text-label-sm font-bold text-on-surface-variant">
            Password @if (! isset($user))<span class="text-error">*</span>@endif
        </label>
        <input type="password" name="password" autocomplete="new-password" {{ isset($user) ? '' : 'required' }} class="{{ $inputClass }}"/>
        @isset($user)<p class="text-label-sm text-on-surface-variant">Leave blank to keep the current password.</p>@endisset
    </div>
    <div class="space-y-1">
        <label class="text-label-sm font-bold text-on-surface-variant">Confirm Password @if (! isset($user))<span class="text-error">*</span>@endif</label>
        <input type="password" name="password_confirmation" autocomplete="new-password" {{ isset($user) ? '' : 'required' }} class="{{ $inputClass }}"/>
    </div>
</div>

<div class="mt-lg flex items-center justify-end gap-3 border-t border-outline-variant pt-lg">
    <a href="{{ route('users.index') }}" class="rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant transition-colors hover:bg-surface-container-low">Cancel</a>
    <button type="submit" class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
        {{ isset($user) ? 'Update User' : 'Create User' }}
    </button>
</div>
