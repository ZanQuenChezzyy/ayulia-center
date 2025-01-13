<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const selectKelas = document.getElementById('kelas_id');
        const orderSummary = {
            deskripsi: document.getElementById('kelas-deskripsi'),
            nama: document.getElementById('kelas-nama'),
            harga: document.getElementById('kelas-harga'),
            tingkatan: document.getElementById('kelas-tingkatan'),
            jadwal: document.getElementById('kelas-jadwal'),
            pertemuan: document.getElementById('kelas-pertemuan'),
            instruktur: document.getElementById('kelas-instruktur'),
        };

        // Fungsi untuk memformat waktu dari HH:mm:ss ke HH:mm
        function formatTime(time) {
            if (!time || time === '-') return '-';
            const [hours, minutes] = time.split(':');
            return `${hours}:${minutes}`;
        }

        selectKelas.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const harga = selectedOption.getAttribute('data-harga') || 0;
            const deskripsi = selectedOption.getAttribute('data-deskripsi') || '-';
            const tingkatan = selectedOption.getAttribute('data-tingkatan') || '-';
            const jamMulai = formatTime(selectedOption.getAttribute('data-jam-mulai')) || '-';
            const jamSelesai = formatTime(selectedOption.getAttribute('data-jam-selesai')) || '-';
            const pertemuan = selectedOption.getAttribute('data-pertemuan') || '-';
            const instruktur = selectedOption.getAttribute('data-instruktur') || '-';

            // Format harga ke Rupiah
            const formattedHarga = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            }).format(harga);

            // Update Order Summary
            orderSummary.nama.textContent = `${selectedOption.text}`;
            orderSummary.deskripsi.innerHTML = deskripsi;
            orderSummary.harga.textContent = `${formattedHarga}`;
            orderSummary.tingkatan.textContent = `${tingkatan}`;
            orderSummary.jadwal.textContent = `${jamMulai} - ${jamSelesai} WITA`;
            orderSummary.pertemuan.textContent = `${pertemuan} Kali Pertemuan`;
            orderSummary.instruktur.textContent = `${instruktur}`;
        });
    });
</script>
