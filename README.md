<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
<div align="center">

<img src="public/logo.png" alt="Logo SIG-GKS" width="120" height="120">

# SIG-GKS
**Sistem Informasi Gereja Terintegrasi & Komprehensif**

<em>"Melayani dengan Kasih, Mengelola dengan Amanah."</em>

</div>

<p align="center">
<a href="#-fitur-unggulan">Fitur</a> •
<a href="#-teknologi">Teknologi</a> •
<a href="#-instalasi">Instalasi</a> •
<a href="#-akses--role">Hak Akses</a> •
<a href="#-lisensi--larangan">Lisensi</a>
</p>

<p align="center">
<img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
<img src="https://img.shields.io/badge/Livewire-3.x-4E5D94?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
<img src="https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
<img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📖 Tentang Proyek
SIG-GKS (Sistem Informasi Gereja Kristen Sumba) adalah solusi perangkat lunak **enterprise-grade** yang dirancang untuk mendigitalisasi administrasi gereja secara menyeluruh.  
Dengan pendekatan **Mobile-First** dan **User-Friendly**, sistem ini menjembatani kebutuhan administrasi klerikal dengan **transparansi finansial modern**.

Sistem ini mendukung **penggunaan jangka panjang (>20 Tahun)** dengan:
- Fitur audit trail ketat  
- Manajemen data fleksibel  
- Keamanan berbasis role  

---

## 🚀 Fitur Unggulan

### 1. 👥 Database Jemaat Terpadu (360° View)
- **Manajemen Keluarga (KK):** Peta domisili, status keaktifan, dan relasi keluarga.  
- **Data Jiwa (Member):** Rekam jejak sakramen (Baptis, Sidi, Nikah) & mutasi jemaat.  
- **Pencarian Cerdas:** Cari jemaat berdasarkan nama, NIK, atau wilayah dalam hitungan detik.

### 2. 💰 Manajemen Keuangan & RAPB (Audit Ready)
- **Multi-Kas/Dompet:** Tunai, Bank, dan Pembangunan.  
- **RAPB Dinamis:** Pos anggaran yang bisa disesuaikan (Parent-Child).  
- **Jurnal Transaksi:** Pemasukan, Pengeluaran, dan Pindah Buku dengan validasi.  
- **Payroll System:** Gaji pegawai terintegrasi langsung ke pos anggaran.  
- **Manajemen Lelang:** Catat barang lelang, pemenang, dan status piutang jemaat.

### 3. 🗓️ Penjadwalan & Pelayanan
- **PKS Scheduler:** Generator jadwal ibadah rumah tangga per wilayah (otomatis 2 bulan).  
- **Manajemen Pelayan:** Penugasan Majelis, Pendeta, Petugas Liturgi.  
- **Verifikasi Kolekte:** Alur validasi uang dari Majelis Wilayah ke Bendahara Pusat.  
- **Anti-Bentrok:** Peringatan jika pelayan dijadwalkan ganda.

### 4. 📝 Sekretariat Digital
- **Surat Menyurat:** Cetak otomatis Surat Baptis, Sidi, Nikah, dan Keterangan Anggota.  
- **Penomoran Otomatis:** Generator nomor surat berdasarkan format klasis/sinode.  
- **Cetak PDF:** Template dokumen resmi siap cetak (A4).

### 5. 🌐 Website Publik Terintegrasi
- **Landing Page Modern:** Profil gereja, visi-misi, dan galeri kegiatan.  
- **Warta Jemaat Digital:** Publikasi berita & jadwal ibadah real-time.  
- **Transparansi Keuangan:** Widget saldo kas untuk akuntabilitas publik.

---

## 🛠 Teknologi
| Komponen | Versi / Keterangan |
|----------|------------------|
| Framework | Laravel 11 |
| Frontend | Blade + Livewire 3 |
| Styling | Tailwind CSS + Alpine.js |
| Database | MySQL 8.0 |
| PDF Generation | DomPDF |
| Authorization | Spatie Laravel Permission |

---

## **💻 Instalasi**   
```
git clone https://github.com/username/sig-gks.git
cd sig-gks
```
1. **Install Dependensi**  
```
composer install
npm install && npm run build
```
3. **Konfigurasi environment**
```
cp .env.example .env
php artisan key:generate

```
> Sesuaikan koneksi database di file .env.

4. **Migrasi & seeding database**
```
php artisan migrate:fresh --seed --class=FullDeploymentSeeder
php artisan serve
```

## 🔐 Akses & Role
| Role       | Email                                         | Password | Akses Utama                  |
| ---------- | --------------------------------------------- | -------- | ---------------------------- |
| Admin      | [admin@gks.id](mailto:admin@gks.id)           | password | Akses Penuh (System Setting) |
| Pendeta    | [pendeta@gks.id](mailto:pendeta@gks.id)       | password | Supervisi, Laporan, Approval |
| Bendahara  | [bendahara@gks.id](mailto:bendahara@gks.id)   | password | Keuangan, Payroll, Aset      |
| Sekretaris | [sekretaris@gks.id](mailto:sekretaris@gks.id) | password | Database, Jadwal, Surat      |
| Majelis    | [majelis1@gks.id](mailto:majelis1@gks.id)     | password | Input PKS, Jadwal Pribadi    |

> [!NOTE]
> Segera ganti password default setelah deployment produksi.

### ⚠️ Lisensi & Larangan

Perangkat lunak ini **PROPRIETARY** dan dilindungi oleh **UU Hak Cipta**.

#### ✅ Diizinkan
- Digunakan oleh **GKS Jemaat Reda Pada** untuk operasional gereja.
- Dimodifikasi secara **internal** untuk kebutuhan jemaat.
- Dicadangkan (backup) demi **keamanan data**.

#### 🚫 Dilarang
- Menjual kembali kode atau aplikasi **tanpa izin**.
- Distribusi ulang secara publik **tanpa lisensi resmi**.
- Menghapus hak cipta atau klaim kepemilikan pada kode sumber.
- Menggunakan kode ini untuk membangun produk **komersial lain**.

## 📞 Hubungi Developer

Sebelum menggunakan atau melakukan modifikasi pada SIG-GKS, harap **menghubungi tim developer** untuk mendapatkan izin dan panduan resmi.


---
### 🛡️ Kontak, Kontribusi & Donasi
Jika Anda ingin berkontribusi atau memiliki pertanyaan mengenai sistem ini, silakan hubungi pengembang melalui:

- **WhatsApp**: [082247459503](https://wa.me/6287750124895)
- **Kontribusi**:
    1. Fork repositori ini.
    2. Buat branch fitur baru (`git checkout -b feature/NamaFitur`).
    3. Commit perubahan Anda (`git commit -m 'Menambah fitur X'`).
    4. Push ke branch tersebut.
    5. Buat Pull Request.

- **Dukungan / Donasi**:  
Jika sistem ini membantu Anda dan ingin mendukung pengembangannya, Anda dapat memberikan apresiasi melalui **Saweria**:  

[![Dukung Saya di Saweria](https://img.shields.io/badge/Dukung-Saweria-orange?style=flat-square)](https://saweria.co/nanutechsolution)  

> Setiap kontribusi sangat berarti untuk keberlanjutan pengembangan fitur-fitur baru.  

> ⚠️ Pastikan untuk mendapatkan izin resmi sebelum mendistribusikan, menjual, atau menggunakan kode ini di luar GKS Jemaat Reda Pada.

