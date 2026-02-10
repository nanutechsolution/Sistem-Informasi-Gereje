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

## 💻 Instalasi
1. **Clone Repositori**  
```bash
git clone https://github.com/username/sig-gks.git
cd sig-gks
1. **ClInstall Dependensi**  
```bash
composer install
npm install && npm run build