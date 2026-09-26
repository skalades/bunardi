# Design Doc — Sistem Manajemen Catering
**Versi:** 0.1 (Draft)
**Terkait:** PRD-Sistem-Manajemen-Catering.md

---

## 1. Prinsip Desain Utama: Inventori Berbasis Ledger (Bukan Berbasis Stok Tetap)

Karena jumlah stok aktual tidak diketahui, sistem **tidak** dirancang seperti inventori retail biasa (stok = angka pasti yang dikurangi/tambah). Sebagai gantinya, dipakai pendekatan **ledger/log transaksi**:

- Setiap barang punya **riwayat transaksi** (keluar, masuk, opname).
- **Stok bukan angka master yang diedit langsung** — stok adalah **hasil hitung** dari: `checkpoint_terakhir + total_masuk − total_keluar` sejak checkpoint tersebut.
- **Stok opname** = checkpoint baru. Tidak menghapus histori lama, hanya menandai "per tanggal ini, jumlah fisik = X".
- Barang yang belum pernah di-opname tetap bisa dipakai dalam transaksi keluar/masuk — sistem hanya menampilkan estimasi dengan label "belum terverifikasi".

Pendekatan ini mirip prinsip *append-only ledger* pada sistem akuntansi: jangan pernah menimpa data lama, selalu tambah entri baru.

```mermaid
flowchart LR
    A[Checkpoint Opname\nqty aktual: 50] --> B[Transaksi Keluar\n-10 untuk Order #123]
    B --> C[Transaksi Masuk\n+8 kembali dari Order #123]
    C --> D[Estimasi Stok Saat Ini\n= 50-10+8 = 48]
    D --> E[Opname Berikutnya\nqty aktual: 45]
    E -->|selisih -3 = susut/hilang| F[Checkpoint Baru]
```

---

## 2. Arsitektur Sistem (High Level)

```mermaid
flowchart TB
    subgraph Client
        WebApp[Web App Responsif\nAdmin & Crew]
    end
    subgraph Backend
        API[REST API]
        Auth[Auth & Role Management]
        OrderSvc[Order Service]
        InvSvc[Inventory Ledger Service]
        InvoiceSvc[Invoice Service]
        ReportSvc[Report Service]
    end
    subgraph Data
        DB[(Database\nPostgreSQL/MySQL)]
        FileStore[(File Storage\nPDF Invoice, Foto Barang)]
    end

    WebApp --> API
    API --> Auth
    API --> OrderSvc
    API --> InvSvc
    API --> InvoiceSvc
    API --> ReportSvc
    OrderSvc --> DB
    InvSvc --> DB
    InvoiceSvc --> DB
    InvoiceSvc --> FileStore
    ReportSvc --> DB
```

**Rekomendasi Tech Stack (sederhana, mudah dikelola tim kecil):**
- Frontend: web responsif (React/Next.js atau bisa lebih sederhana pakai Laravel Blade jika tim familiar PHP)
- Backend: Node.js (Express/NestJS) atau Laravel (PHP) — pilih sesuai keahlian tim
- Database: PostgreSQL atau MySQL
- File storage: lokal server dulu, bisa upgrade ke cloud storage (S3-compatible) belakangan
- Hosting: VPS sederhana cukup untuk skala 1 bisnis catering

*(Stack di atas rekomendasi, bisa disesuaikan dengan tim developer yang akan mengerjakan.)*

---

## 3. Data Model (ERD)

```mermaid
erDiagram
    USER ||--o{ ORDER : membuat
    USER ||--o{ INVENTORY_TRANSACTION : mencatat
    USER ||--o{ ORDER_ASSIGNMENT : ditugaskan_pada
    CLIENT ||--o{ ORDER : memesan
    ORDER ||--o{ ORDER_ITEM : berisi
    ORDER ||--o{ INVENTORY_TRANSACTION : terkait
    ORDER ||--o{ ORDER_ASSIGNMENT : memiliki
    ORDER ||--|| INVOICE : menghasilkan
    INVOICE ||--o{ INVOICE_ITEM : berisi
    INVOICE ||--o{ PAYMENT : menerima
    ITEM ||--o{ INVENTORY_TRANSACTION : dicatat_pada
    ITEM ||--o{ STOCK_CHECKPOINT : diopname_pada
    ITEM ||--o{ ORDER_ITEM : direncanakan_pada

    USER {
        int id PK
        string nama
        string role
        string kontak
    }
    CLIENT {
        int id PK
        string nama
        string kontak
        string alamat
    }
    ORDER {
        int id PK
        int client_id FK
        date tanggal_acara
        string lokasi
        int jumlah_pax
        string status
        text catatan
        decimal harga_pax_normal
        decimal harga_pax_deal
        decimal biaya_tambahan
        decimal grand_total
    }
    ORDER_ITEM {
        int id PK
        int order_id FK
        int item_id FK
        int qty_rencana
    }
    ORDER_ASSIGNMENT {
        int id PK
        int order_id FK
        int user_id FK
        string divisi "Catering/Decor/Laundry/dll"
        boolean is_pic "Penanggung jawab?"
    }
    ITEM {
        int id PK
        string nama
        string kategori
        string satuan
    }
    INVENTORY_TRANSACTION {
        int id PK
        int item_id FK
        int order_id FK "nullable"
        string tipe "keluar/masuk"
        int qty
        string kondisi "baik/rusak/hilang"
        string alasan
        int pic_user_id FK
        datetime waktu
    }
    STOCK_CHECKPOINT {
        int id PK
        int item_id FK
        int qty_aktual
        int selisih_vs_estimasi
        int dicatat_oleh_user_id FK
        datetime waktu
    }
    INVOICE {
        int id PK
        int order_id FK
        string status "draft/terkirim/lunas"
        decimal total
        date tanggal_terbit
        date tanggal_lunas
    }
    INVOICE_ITEM {
        int id PK
        int invoice_id FK
        string deskripsi
        decimal harga_satuan
        int qty
    }
    PAYMENT {
        int id PK
        int invoice_id FK
        decimal nominal
        string tipe "DP/Pelunasan/Cicilan"
        string metode_bayar "Cash/Transfer"
        date tanggal
    }
```

---

## 4. Alur Utama (Flow)

### 4.1 Alur Order → Invoice

```mermaid
sequenceDiagram
    actor Admin
    participant Sistem
    actor Crew
    actor Klien

    Admin->>Sistem: Buat Order baru (data klien, tanggal, lokasi)
    Admin->>Sistem: Tambah rencana barang yang dipakai
    Sistem-->>Admin: Order status "Dikonfirmasi"
    Note over Crew,Sistem: H-1 / hari H acara
    Crew->>Sistem: Catat barang KELUAR (qty, PIC, order terkait)
    Sistem-->>Sistem: Update estimasi stok (berkurang)
    Note over Crew: Acara berlangsung & selesai
    Crew->>Sistem: Catat barang MASUK kembali (qty, kondisi)
    Sistem-->>Sistem: Update estimasi stok (bertambah)
    Sistem-->>Admin: Notifikasi jika ada barang belum kembali
    Admin->>Sistem: Buat Invoice dari Order
    Sistem-->>Admin: Invoice PDF ter-generate
    Admin->>Klien: Kirim Invoice
    Klien-->>Admin: Pembayaran
    Admin->>Sistem: Update status Invoice "Lunas"
```

### 4.2 Alur Stok Opname (Koreksi Berkala)

```mermaid
sequenceDiagram
    actor Gudang
    participant Sistem

    Gudang->>Sistem: Pilih barang untuk opname
    Sistem-->>Gudang: Tampilkan estimasi stok saat ini
    Gudang->>Sistem: Input jumlah fisik aktual (hasil hitung manual)
    Sistem-->>Sistem: Hitung selisih (estimasi vs aktual)
    Sistem-->>Gudang: Simpan sebagai checkpoint baru
    Sistem-->>Gudang: Tampilkan laporan selisih (indikasi susut/hilang)
```

---

## 5. Rancangan Halaman (Screens)

| Halaman | Fungsi Utama | Peran Akses |
|---|---|---|
| Dashboard | Ringkasan order mendatang, barang belum kembali, invoice belum lunas | Admin |
| Daftar Order | List & filter order, buat order baru | Admin |
| Detail Order | Info order + daftar barang + penugasan kru (PIC & Divisi) + tombol buat invoice | Admin, Crew (view) |
| Input Barang Keluar/Masuk | Form cepat: pilih order, pilih barang, qty, kondisi | Crew, Admin |
| Master Barang & Stok | List semua barang + estimasi stok + status opname terakhir | Admin, Gudang |
| Stok Opname | Form input qty fisik aktual per barang | Gudang, Admin |
| Riwayat Transaksi Barang | Log lengkap keluar/masuk per barang | Admin, Gudang |
| Invoice | List invoice, buat/edit, export PDF, update status bayar | Admin |
| Laporan | Barang rawan hilang, rekap order per periode | Admin |

---

## 6. Contoh Endpoint API (Ringkas)

```
POST   /orders                     - buat order baru
GET    /orders/:id                 - detail order
PATCH  /orders/:id/status          - update status order

POST   /inventory/transactions     - catat barang keluar/masuk
GET    /inventory/items/:id/ledger - riwayat transaksi 1 barang
GET    /inventory/items/:id/stock  - estimasi stok berjalan

POST   /inventory/checkpoints      - input hasil stok opname
GET    /inventory/checkpoints/:item_id/history - riwayat opname

POST   /invoices                   - generate invoice dari order
PATCH  /invoices/:id/status        - update status pembayaran
GET    /invoices/:id/pdf           - export PDF
```

---

## 7. Pertimbangan Desain Tambahan

- **Input di lapangan harus cepat**: form catat barang keluar/masuk sebaiknya minim klik (pilih order aktif → pilih barang dari daftar favorit/sering dipakai → isi qty → simpan).
- **Tidak ada tombol "hapus" untuk transaksi lama** — kalau salah input, buat entri koreksi baru (tetap sesuai prinsip ledger di Bagian 1), supaya jejak audit tetap utuh.
- **Barang tanpa data stok awal tetap bisa dipakai** — sistem tidak boleh mem-block input hanya karena stok belum diketahui. Label "estimasi belum terverifikasi" cukup sebagai indikator visual.
- **Skalabilitas ke Fase 2**: struktur data payroll & pengeluaran (dari dokumen operasional) bisa jadi modul terpisah yang terhubung ke `USER` dan `ORDER` tanpa mengubah desain inventori di atas.

---

## 8. Rencana Implementasi Bertahap

1. **Sprint 1–2**: Master data (Item, Client, User/Role) + Modul Order dasar
2. **Sprint 3–4**: Modul Inventory Ledger (keluar/masuk) + estimasi stok
3. **Sprint 5**: Modul Stok Opname
4. **Sprint 6–7**: Modul Invoice + export PDF
5. **Sprint 8**: Dashboard & Laporan dasar
6. **Fase 2 (menyusul)**: Payroll, Pengeluaran Operasional, Notifikasi
