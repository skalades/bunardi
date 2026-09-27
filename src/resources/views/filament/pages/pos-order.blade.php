<x-filament-panels::page>
    <style>
        .pos-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            align-items: start;
        }
        @media (min-width: 768px) {
            .pos-grid {
                grid-template-columns: 2fr 1fr;
            }
        }
        .pos-products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }
        .pos-input {
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #111827;
        }
    </style>
    
    <div class="pos-grid">
        
        <!-- Bagian Kiri: Daftar Produk -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Tab Kategori -->
            <x-filament::section>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <x-filament::button 
                        wire:click="setActiveTab('Catering')"
                        color="{{ $activeTab === 'Catering' ? 'primary' : 'gray' }}"
                        size="lg">
                        <x-heroicon-o-cake style="width: 1.5rem; height: 1.5rem; display: inline; margin-right: 0.5rem;" /> Paket Catering
                    </x-filament::button>
                    
                    <x-filament::button 
                        wire:click="setActiveTab('Sewa Peralatan')"
                        color="{{ $activeTab === 'Sewa Peralatan' ? 'primary' : 'gray' }}"
                        size="lg">
                        <x-heroicon-o-wrench-screwdriver style="width: 1.5rem; height: 1.5rem; display: inline; margin-right: 0.5rem;" /> Sewa Peralatan
                    </x-filament::button>
                </div>
            </x-filament::section>

            <!-- Grid Produk -->
            <div class="pos-products-grid">
                @foreach ($items as $item)
                    <x-filament::section style="padding: 0; display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                        <div style="margin-bottom: 1rem;">
                            <h3 style="font-weight: bold; font-size: 1.1rem; margin-bottom: 0.25rem; color: #111827;">{{ $item->nama }}</h3>
                            <p style="color: #d97706; font-weight: 600;">
                                Rp {{ number_format($item->harga, 0, ',', '.') }} 
                                <span style="font-size: 0.8rem; font-weight: normal; color: #6b7280;">/ {{ $item->satuan }}</span>
                            </p>
                        </div>
                        <x-filament::button wire:click="addToCart({{ $item->id }})" size="sm" style="width: 100%;">
                            <x-heroicon-o-plus style="width: 1rem; height: 1rem; display: inline;" /> Tambah
                        </x-filament::button>
                    </x-filament::section>
                @endforeach
            </div>

            @if($items->isEmpty())
                <x-filament::section>
                    <div style="text-align: center; padding: 2rem; color: gray;">
                        Tidak ada item di kategori ini.
                    </div>
                </x-filament::section>
            @endif
        </div>

        <!-- Bagian Kanan: Keranjang (Cart) -->
        <div style="position: sticky; top: 2rem;">
            <x-filament::section>
                <x-slot name="heading">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <x-heroicon-o-shopping-cart style="width: 1.5rem; height: 1.5rem;" /> Detail Order
                    </div>
                </x-slot>

                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.9rem;">Klien</label>
                        <select wire:model="clientId" class="pos-input">
                            <option value="">-- Pilih Klien --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.9rem;">Tanggal Acara</label>
                        <input type="date" wire:model="tanggalAcara" class="pos-input" />
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 0.25rem; font-size: 0.9rem;">Lokasi</label>
                        <input type="text" wire:model="lokasi" placeholder="Masukkan lokasi acara..." class="pos-input" />
                    </div>
                </div>

                <hr style="border-color: #e5e7eb; margin: 1rem 0;" />
                
                <h3 style="font-weight: bold; margin-bottom: 1rem;">Keranjang</h3>
                
                <div style="max-height: 300px; overflow-y: auto; padding-right: 0.5rem;">
                    @forelse($cart as $id => $cartItem)
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #f3f4f6; padding-bottom: 0.75rem;">
                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 0.95rem;">{{ $cartItem['nama'] }}</div>
                                <div style="color: #d97706; font-size: 0.85rem; font-weight: 600;">Rp {{ number_format($cartItem['harga'], 0, ',', '.') }}</div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input 
                                    type="number" 
                                    wire:model.lazy="cart.{{ $id }}.qty"
                                    min="1"
                                    style="width: 60px; text-align: center; padding: 0.25rem; border-radius: 0.375rem; border: 1px solid #d1d5db;"
                                />
                                <x-filament::button color="danger" size="sm" wire:click="removeFromCart({{ $id }})" tooltip="Hapus">
                                    <x-heroicon-o-trash style="width: 1rem; height: 1rem;" />
                                </x-filament::button>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 1.5rem; color: #6b7280; background-color: #f9fafb; border-radius: 0.5rem; font-size: 0.9rem;">
                            Keranjang kosong. Silakan pilih produk.
                        </div>
                    @endforelse
                </div>

                <hr style="border-color: #e5e7eb; margin: 1rem 0;" />

                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; padding: 1rem; background-color: #f9fafb; border-radius: 0.5rem;">
                    <span>Total</span>
                    <span style="color: #d97706;">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                </div>
                
                <x-filament::button wire:click="saveOrder" color="success" size="lg" style="width: 100%;">
                    Proses Order
                </x-filament::button>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
