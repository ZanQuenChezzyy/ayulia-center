<script type="text/javascript">
    $('#submit-button-pesan').on('click', function(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Pastikan data yang Anda masukkan sudah benar.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!',
            customClass: {
                confirmButton: 'btn btn-info me-1',
                cancelButton: 'btn btn-label-secondary'
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $('#form-pesan').attr('action'),
                    type: 'POST',
                    data: new FormData($('#form-pesan')[0]),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#form-pesan')[0].reset();
                        $('.text-danger').remove();
                        Swal.fire({
                            title: 'Pesan Berhasil Dikirim!',
                            text: "{{ session('success') }}", // Pesan sukses
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Oke!',
                            customClass: {
                                confirmButton: 'btn btn-primary me-1',
                                cancelButton: 'btn btn-label-secondary'
                            },
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) { // Jika ada kesalahan validasi
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                $('#' + field).next('.text-danger')
                                    .remove();
                                $('#' + field).after(
                                    '<div class="text-danger mt-2">' + messages[
                                        0] + '</div>');
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terdapat kesalahan saat mengirim data.',
                                icon: 'error',
                            });
                        }
                    }
                });
            }
        });
    });
</script>
