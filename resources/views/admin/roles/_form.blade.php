@php
    $selected = collect(old('permissions', $rolePermissions ?? []));
    $actionMeta = [
        'view' => ['Visibility', 'visibility'],
        'create' => ['Create', 'add'],
        'edit' => ['Edit', 'edit'],
        'delete' => ['Delete', 'delete'],
    ];
@endphp

<div class="space-y-1">
    <label class="text-label-sm font-bold text-on-surface-variant">Role Name <span class="text-error">*</span></label>
    <input type="text" name="name" value="{{ old('name', $role->name ?? '') }}" maxlength="255" required
           placeholder="e.g. campus-coordinator"
           class="w-full max-w-md rounded-lg border border-outline-variant bg-white px-md py-2 outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"/>
    <p class="text-label-sm text-on-surface-variant">Letters, numbers, spaces and hyphens only.</p>
</div>

<div class="mt-lg">
    <div class="mb-md flex items-center justify-between">
        <h3 class="font-headline-md text-headline-md text-on-surface">Permissions</h3>
        <span class="text-label-sm text-on-surface-variant">Tick the actions this role may perform per module.</span>
    </div>

    <div class="grid grid-cols-1 gap-md md:grid-cols-2 xl:grid-cols-3">
        @foreach ($modules as $module => $permissions)
            <div x-data class="rounded-xl border border-outline-variant bg-surface-container-low/40 p-md">
                <div class="mb-2 flex items-center justify-between border-b border-outline-variant pb-2">
                    <span class="font-bold text-on-surface">{{ Str::headline($module) }}</span>
                    <label class="flex cursor-pointer items-center gap-1 text-label-sm text-on-surface-variant">
                        <input type="checkbox"
                               @change="$root.querySelectorAll('input.perm').forEach(c => c.checked = $event.target.checked)"
                               class="rounded border-outline-variant text-primary focus:ring-primary"/>
                        All
                    </label>
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-2">
                    @foreach ($permissions as $permission)
                        @php $action = \Illuminate\Support\Str::afterLast($permission->name, '.'); @endphp
                        <label class="flex cursor-pointer items-center gap-1.5 text-body-md text-on-surface-variant">
                            <input type="checkbox" class="perm rounded border-outline-variant text-primary focus:ring-primary"
                                   name="permissions[]" value="{{ $permission->name }}"
                                   @checked($selected->contains($permission->name))/>
                            <span class="material-symbols-outlined text-[16px] opacity-70">{{ $actionMeta[$action][1] ?? 'check' }}</span>
                            {{ $actionMeta[$action][0] ?? Str::headline($action) }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-lg flex items-center justify-end gap-3 border-t border-outline-variant pt-lg">
    <a href="{{ route('roles.index') }}" class="rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant transition-colors hover:bg-surface-container-low">Cancel</a>
    <button type="submit" class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
        {{ isset($role) ? 'Update Role' : 'Create Role' }}
    </button>
</div>
