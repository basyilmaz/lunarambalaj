<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Hizli islemler</x-slot>
        <div class="grid gap-3 md:grid-cols-4">
            <a href="{{ route('filament.admin.resources.products.create') }}" class="rounded-lg border px-4 py-3 hover:bg-gray-50">
                Yeni urun
            </a>
            <a href="{{ route('filament.admin.resources.posts.create') }}" class="rounded-lg border px-4 py-3 hover:bg-gray-50">
                Yeni blog yazisi
            </a>
            <a href="{{ route('filament.admin.resources.leads.index') }}" class="rounded-lg border px-4 py-3 hover:bg-gray-50">
                Leadleri ac
            </a>
            <a href="{{ route('filament.admin.resources.settings.index') }}" class="rounded-lg border px-4 py-3 hover:bg-gray-50">
                Site ayarlari
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
