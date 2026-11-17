# Keterangan menu ERP Dekorasi

Menu di sidebar admin tahap 1. Setiap orang hanya melihat menu yang sesuai perannya. Akun tanpa izin tidak bisa membuka halaman itu, meskipun alamatnya diketik langsung.

## Dasbor

Halaman awal setelah masuk. Menampilkan ringkasan jumlah pelanggan, vendor, gudang, dan kategori, plus kartu akun yang sedang dipakai. Angka yang muncul mengikuti data yang boleh dilihat oleh peran tersebut.

## Master Data

Kelompok data dasar yang nanti dipakai proses penjualan, proyek, dan gudang. Belum ada transaksi di tahap ini.

### Prospek

Calon klien yang belum menjadi pelanggan. Disimpan nama, jenis, telepon, email, alamat, minat acara (pernikahan, gathering, pameran, dan sejenisnya), status (baru, dihubungi, tertarik, atau tidak lanjut), dan catatan. Dari halaman prospek, tombol Jadikan pelanggan menyalin data itu menjadi pelanggan dan menandai prospek sudah menjadi pelanggan. Data yang dihapus masih bisa dipulihkan.

### Pelanggan

Klien dekorasi yang sudah tercatat, untuk wedding, acara perusahaan, atau pameran. Disimpan nama, jenis (perorangan atau perusahaan), telepon, email, alamat, dan catatan. Pelanggan yang berasal dari prospek menampilkan tautan ke prospek asalnya. Data yang dihapus masih bisa dipulihkan.

### Vendor

Pemasok atau rekanan, misalnya sewa, transportasi, percetakan, bunga, atau lainnya. Disimpan nama, kategori, narahubung, telepon, email, dan alamat. Di halaman detail, tab Harga mencatat barang atau jasa beserta harga modal, harga jual, jumlah, dan laba kotor. Laba kotor dihitung dari (harga jual − harga modal) × jumlah, lalu dijumlahkan untuk vendor itu. Tab Dokumen kerja sama dipakai bila ada berkas perjanjian, dan berkas itu tidak dibuka dari internet. Data vendor yang dihapus masih bisa dipulihkan.

### Kategori

Pengelompokan barang atau pekerjaan dekorasi, dan bisa punya induk. Contoh: Dekorasi sebagai induk, Backdrop sebagai anak. Di halaman detail, tab Subkategori menampilkan anak langsung. Anak yang dibuat dari tab itu otomatis memakai kategori yang sedang dibuka sebagai induk. Kategori yang masih punya anak tidak bisa dihapus. Slug terisi otomatis dari nama jika dikosongkan.

### Satuan

Satuan hitung barang, misalnya buah (`pcs`), set, atau meter (`m`). Simbol disimpan dalam huruf kecil dan tidak boleh kembar.

### Barang

Barang dekorasi milik perusahaan, misalnya kursi, tenda, atau bunga. Disimpan kode, nama, kategori, satuan, pemasok, dan catatan. Kode disimpan dalam huruf besar. Nominal di halaman barang adalah jumlah nilai barang itu di seluruh gudang.

### Gudang

Lokasi penyimpanan barang. Disimpan nama, kode, alamat, dan status aktif. Kode diubah menjadi huruf besar dan tidak boleh kembar. Di halaman detail, tab Penempatan mencatat rak atau zona di dalam gudang. Tab Isi gudang mencatat barang, jumlah, harga beli awal, kondisi, dan tanggal beli. Nominal baris dihitung dari jumlah × harga beli awal, lalu dijumlahkan menjadi nominal gudang. Penempatan yang masih berisi barang tidak bisa dihapus.

## Pengaturan

Kelompok data perusahaan dan akun pemakai.

### Perusahaan

Identitas perusahaan yang memakai ERP ini: nama, nama legal, email, telepon, NPWP, alamat, dan logo. Dipakai sebagai pemilik data pelanggan, vendor, gudang, dan pengguna. Perusahaan tidak dihapus dari menu ini.

### Pengguna

Daftar akun yang boleh masuk ke admin. Setiap akun punya nama, email, kata sandi, peran, dan status aktif. Akun yang dimatikan tidak bisa masuk. Seseorang tidak dapat menghapus akunnya sendiri.

## Akses

Kelompok pengaturan siapa boleh melakukan apa.

### Peran

Daftar peran dan izinnya. Peran yang tersedia: Super Admin, Director, Sales, Project Manager, Warehouse, Finance, dan Staff. Di sini izin tiap peran bisa dilihat dan diubah, misalnya siapa yang boleh menambah pelanggan atau hanya boleh melihat gudang. Super Admin tetap bisa mengakses seluruh panel.

## Audit

Kelompok jejak perubahan data.

### Log aktivitas

Catatan perubahan pada data penting: siapa mengubah, data apa, peristiwa apa (dibuat, diubah, atau dihapus), dan kapan. Isinya hanya untuk dilihat, tidak untuk diedit dari menu ini. Catatan yang sama juga muncul di tab Log aktivitas pada halaman detail prospek, pelanggan, vendor, kategori, satuan, gudang, dan pengguna, khusus untuk data yang sedang dibuka. Tab itu hanya tampil bagi peran yang boleh melihat log aktivitas.
