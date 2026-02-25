<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Marathon Events</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        .gradient-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div id="loadingScreen" class="fixed inset-0 bg-white z-50 flex flex-col items-center justify-center">
        <div class="w-12 h-12 border-4 border-gray-200 border-t-blue-500 rounded-full animate-spin"></div>
    </div>

    <div id="content" class="hidden">
        <header class="gradient-header shadow-lg p-4 text-white">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="/admin/dashboard" class="bg-white/20 p-2 rounded-lg hover:bg-white/30 transition"><i class="fas fa-arrow-left"></i></a>
                    <h1 class="text-xl font-bold">Profil Admin</h1>
                </div>
                <div class="w-10 h-10 rounded-full border border-white/50 overflow-hidden bg-white">
                    <img src="{{ $user->foto_profil ? asset('storage/profiles/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama) }}" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="relative">
                            <img id="displayPhoto" src="{{ $user->foto_profil ? asset('storage/profiles/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama) }}" class="w-32 h-32 rounded-full object-cover border-4 border-blue-50 shadow-md">
                            <button onclick="openEditModal()" class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full hover:bg-blue-700 transition"><i class="fas fa-camera text-xs"></i></button>
                        </div>
                        <div class="text-center md:text-left">
                            <h2 class="text-2xl font-bold text-gray-800">{{ $user->nama }}</h2>
                            <p class="text-gray-500">{{ $user->email }}</p>
                            <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase">Administrator</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Detail Kontak</h3>
                    <div class="space-y-3 text-sm">
                        <p><span class="text-gray-500 block">Telepon:</span> {{ $user->telepon ?? '-' }}</p>
                        <p><span class="text-gray-500 block">Alamat:</span> {{ $user->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </main>

        <div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-xl w-full max-w-md overflow-hidden animate-fade-in shadow-2xl">
                <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                    <h3 class="font-bold">Edit Profil</h3>
                    <button onclick="closeEditModal()"><i class="fas fa-times text-gray-400"></i></button>
                </div>
                <form id="editForm" action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Foto Baru</label>
                        <input type="file" name="foto_profil" accept="image/*" class="text-sm block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ $user->nama }}" required class="w-full border rounded-lg p-2 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Telepon</label>
                        <input type="text" name="telepon" value="{{ $user->telepon }}" class="w-full border rounded-lg p-2 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat</label>
                        <textarea name="alamat" class="w-full border rounded-lg p-2 outline-none focus:ring-2 focus:ring-blue-500">{{ $user->alamat }}</textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-gray-500 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" id="btnSave" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            document.getElementById('loadingScreen').classList.add('hidden');
            document.getElementById('content').classList.remove('hidden');
        });

        const editForm = document.getElementById('editForm');
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSave');
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json' 
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    alert('Profil berhasil diperbarui!');
                    window.location.reload();
                } else {
                    // Menampilkan pesan error spesifik dari Controller
                    alert('Gagal: ' + (result.message || 'Terjadi kesalahan server.'));
                }
            } catch (error) {
                console.error('Network/System Error:', error);
                alert('Kesalahan Jaringan: Pastikan koneksi stabil atau server tidak mati.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Simpan';
            }
        });

        function openEditModal() { document.getElementById('editModal').classList.replace('hidden', 'flex'); }
        function closeEditModal() { document.getElementById('editModal').classList.replace('flex', 'hidden'); }
    </script>
</body>
</html>