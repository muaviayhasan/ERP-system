@extends('layouts.admin')

@section('title', 'SEO Settings')

@section('content')
    <x-settings.page
        title="SEO Settings"
        subtitle="Search engine optimization, meta tags, and indexing rules."
        :action="route('settings.seo.update')">

        {{-- Global SEO + live preview --}}
        <x-settings.section title="Global SEO Configuration" icon="public">
            <div x-data="{
                    title: @js(old('meta_title', $s['meta_title'])),
                    desc: @js(old('meta_description', $s['meta_description'])),
                    url: @js(old('canonical_base', $s['canonical_base'])),
                 }"
                 class="grid grid-cols-1 gap-lg lg:grid-cols-2">
                <div class="space-y-md">
                    <x-settings.field label="Default Meta Title" name="meta_title">
                        <x-settings.input name="meta_title" maxlength="255" x-model="title"/>
                    </x-settings.field>
                    <x-settings.field label="Meta Description" name="meta_description">
                        <x-settings.textarea name="meta_description" rows="3" maxlength="500" x-model="desc"/>
                    </x-settings.field>
                    <x-settings.field label="Meta Keywords" name="meta_keywords" hint="Comma-separated.">
                        <x-settings.input name="meta_keywords" maxlength="500"
                            value="{{ old('meta_keywords', $s['meta_keywords']) }}" placeholder="education, ERP, university"/>
                    </x-settings.field>
                    <div class="grid grid-cols-1 gap-md sm:grid-cols-2">
                        <x-settings.field label="Author Name" name="author_name">
                            <x-settings.input name="author_name" maxlength="255"
                                value="{{ old('author_name', $s['author_name']) }}"/>
                        </x-settings.field>
                        <x-settings.field label="Canonical URL Base" name="canonical_base">
                            <x-settings.input type="url" name="canonical_base" maxlength="255" x-model="url"
                                placeholder="https://example.edu"/>
                        </x-settings.field>
                    </div>
                </div>

                {{-- Live search-result preview --}}
                <div class="space-y-4">
                    <p class="text-label-sm font-bold uppercase tracking-wider text-on-surface-variant">Search Result Preview</p>
                    <div class="rounded-xl border border-outline-variant bg-white p-4 shadow-sm">
                        <div class="truncate text-xs text-[#202124]" x-text="url || 'https://example.edu'"></div>
                        <div class="cursor-pointer text-lg text-[#1a0dab] hover:underline" x-text="title || 'Your page title appears here'"></div>
                        <p class="text-sm leading-snug text-[#4d5156]" x-text="desc || 'Your meta description preview will be shown here as it would appear in search engine results.'"></p>
                    </div>
                    <div class="space-y-3 rounded-lg border border-outline-variant p-4">
                        <x-settings.toggle name="auto_meta" label="Auto-generate meta tags"
                            :checked="old('auto_meta', $s['auto_meta'])"/>
                        <x-settings.toggle name="open_graph" label="Open Graph / Twitter cards"
                            :checked="old('open_graph', $s['open_graph'])"/>
                    </div>
                </div>
            </div>
        </x-settings.section>

        {{-- Indexing & Crawling --}}
        <x-settings.section title="Indexing & Crawling" icon="pageview">
            <div class="grid grid-cols-1 gap-lg lg:grid-cols-2">
                <div class="space-y-4">
                    <div class="rounded-lg border border-outline-variant p-4">
                        <x-settings.toggle name="search_indexing" label="Search Engine Indexing"
                            desc="Allow search engines to index the site."
                            :checked="old('search_indexing', $s['search_indexing'])"/>
                    </div>
                    <div class="rounded-lg border border-outline-variant p-4">
                        <x-settings.toggle name="follow_links" label="Follow Links"
                            desc="Allow crawlers to follow links on pages."
                            :checked="old('follow_links', $s['follow_links'])"/>
                    </div>
                    <x-settings.field label="Robots Meta" name="robots_meta">
                        <x-settings.select name="robots_meta">
                            @foreach (['index, follow', 'noindex, follow', 'index, nofollow', 'noindex, nofollow'] as $opt)
                                <option value="{{ $opt }}" @selected(old('robots_meta', $s['robots_meta']) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </x-settings.select>
                    </x-settings.field>
                </div>
                <x-settings.field label="robots.txt Content" name="robots_txt">
                    <textarea name="robots_txt" rows="9" maxlength="5000"
                        class="w-full rounded-lg bg-on-surface p-4 font-mono text-xs text-surface-bright outline-none transition-all focus:ring-2 focus:ring-primary/50">{{ old('robots_txt', $s['robots_txt']) }}</textarea>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- URL Structure & Performance --}}
        <div class="grid grid-cols-1 gap-lg lg:grid-cols-2">
            <x-settings.section title="URL Structure" icon="link">
                <div class="space-y-md">
                    <div class="rounded-lg border border-outline-variant p-4">
                        <x-settings.toggle name="seo_friendly_urls" label="SEO-friendly URLs (Permalinks)"
                            :checked="old('seo_friendly_urls', $s['seo_friendly_urls'])"/>
                    </div>
                    <x-settings.field label="Slug Formatting" name="slug_format">
                        <x-settings.select name="slug_format">
                            <option value="lowercase-hyphenated" @selected(old('slug_format', $s['slug_format']) === 'lowercase-hyphenated')>lowercase-hyphenated</option>
                            <option value="CamelCase" @selected(old('slug_format', $s['slug_format']) === 'CamelCase')>CamelCase</option>
                            <option value="underscore_separation" @selected(old('slug_format', $s['slug_format']) === 'underscore_separation')>underscore_separation</option>
                        </x-settings.select>
                    </x-settings.field>
                </div>
            </x-settings.section>

            <x-settings.section title="Performance SEO" icon="speed">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach ([
                        'lazy_loading' => 'Lazy Loading',
                        'image_alt_autofill' => 'Image Alt Auto-fill',
                        'minify_assets' => 'JS / CSS Minify',
                        'gzip' => 'Gzip Compression',
                    ] as $key => $label)
                        <div class="rounded-lg border border-outline-variant p-4">
                            <x-settings.toggle name="{{ $key }}" label="{{ $label }}"
                                :checked="old($key, $s[$key])"/>
                        </div>
                    @endforeach
                </div>
            </x-settings.section>
        </div>
    </x-settings.page>
@endsection
