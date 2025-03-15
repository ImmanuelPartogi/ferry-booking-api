@extends('admin.layouts.app')

@section('content')
<div class="bg-white shadow rounded-lg overflow-hidden">
    <!-- Header -->
    <div class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 border-b">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Kapal Ferry</h1>
                <p class="text-gray-600 mt-1">Kelola semua kapal ferry dalam sistem</p>
            </div>
            <a href="{{ route('admin.ferries.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition shadow-sm hover:shadow flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kapal Baru
            </a>
        </div>
    </div>

    <!-- Alerts -->
    <div class="px-6 pt-4">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r" role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Search and Filter Form -->
    <div class="px-6 pb-4">
        <form method="GET" action="{{ route('admin.ferries.index') }}" class="bg-gray-50 p-4 rounded-lg shadow-inner">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                               placeholder="Cari nama kapal..."
                               class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5">
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="">Semua Status</option>
                        <option value="ACTIVE" {{ ($status ?? '') == 'ACTIVE' ? 'selected' : '' }}>Aktif</option>
                        <option value="MAINTENANCE" {{ ($status ?? '') == 'MAINTENANCE' ? 'selected' : '' }}>Pemeliharaan</option>
                        <option value="INACTIVE" {{ ($status ?? '') == 'INACTIVE' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg transition flex items-center">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                    @if($search || $status)
                        <a href="{{ route('admin.ferries.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg transition flex items-center">
                            <i class="fas fa-times mr-2"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="px-6 pb-2">
        <p class="text-sm text-gray-600">
            Menampilkan <span class="font-medium">{{ $ferries->count() }}</span> dari
            <span class="font-medium">{{ $ferries->total() }}</span> kapal
        </p>
    </div>

    <!-- Table -->
    <div class="px-6 pb-6">
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kapal</th>
                        <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas Penumpang</th>
                        <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kapasitas Kendaraan</th>
                        <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ferries as $ferry)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-4 whitespace-nowrap text-sm text-gray-500">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($ferry->image)
                                        <div class="flex-shrink-0 h-10 w-10 mr-3">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $ferry->image) }}" alt="{{ $ferry->name }}">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 h-10 w-10 mr-3 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-ship text-blue-500"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $ferry->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-users text-blue-400 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-900">{{ number_format($ferry->capacity_passenger) }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                    <div class="flex items-center">
                                        <i class="fas fa-motorcycle text-gray-400 mr-2 w-5"></i>
                                        <span>{{ number_format($ferry->capacity_vehicle_motorcycle) }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-car text-gray-400 mr-2 w-5"></i>
                                        <span>{{ number_format($ferry->capacity_vehicle_car) }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-bus text-gray-400 mr-2 w-5"></i>
                                        <span>{{ number_format($ferry->capacity_vehicle_bus) }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-truck text-gray-400 mr-2 w-5"></i>
                                        <span>{{ number_format($ferry->capacity_vehicle_truck) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if ($ferry->status == 'ACTIVE')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                    </span>
                                @elseif($ferry->status == 'MAINTENANCE')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-tools mr-1"></i> Pemeliharaan
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.ferries.show', $ferry->id) }}"
                                        class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 p-2 rounded-lg transition"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.ferries.edit', $ferry->id) }}"
                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 p-2 rounded-lg transition"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.ferries.destroy', $ferry->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-2 rounded-lg transition"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kapal ini? Kapal tidak dapat dihapus jika masih memiliki jadwal terkait.')"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-ship text-gray-300 text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">Tidak ada data kapal</p>
                                    <p class="text-sm text-gray-500 mt-1">Silakan tambahkan kapal baru dengan mengklik tombol "Tambah Kapal Baru"</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="px-6 pb-6">
        {{ $ferries->appends(['search' => $search, 'status' => $status])->links() }}
    </div>
</div>
@endsection
