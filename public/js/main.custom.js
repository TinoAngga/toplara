
document.addEventListener("DOMContentLoaded", function() {
    var lazyloadImages = document.querySelectorAll("img.lazyload");
    var lazyloadThrottleTimeout;

    function lazyload () {
        if(lazyloadThrottleTimeout) {
            clearTimeout(lazyloadThrottleTimeout);
        }

        lazyloadThrottleTimeout = setTimeout(function() {
            var scrollTop = window.pageYOffset;
            lazyloadImages.forEach(function(img) {
                if(img.offsetTop < (window.innerHeight + scrollTop)) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazyload');
                }
            });
            if(lazyloadImages.length == 0) {
                document.removeEventListener("scroll", lazyload);
                window.removeEventListener("resize", lazyload);
                window.removeEventListener("orientationChange", lazyload);
            }
        }, 20);
    }

    document.addEventListener("scroll", lazyload);
    window.addEventListener("resize", lazyload);
    window.addEventListener("orientationChange", lazyload);
});
function selectSearch(element, url) {
    $(element).select2({
        theme: 'bootstrap-5',
        ajax: {
            url: url,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                return {
                    search: params.term
                };
            },
            processResults: function (response) {
                return {
                results: response
                };
            },
            cache: false
        }
    });
}
$(function() {
    if($('.summernote').length > 0) {
        $('.summernote').summernote({
            height: 320,
            minHeight: null,
            maxHeight: null,
            focus: true
        });
    }
});

function reset_button_modal(value = 0) {
    if (value == 0) {
        $('button[name="button_form_modal"]').attr('disabled', 'true');
        $('button[name="button_form_modal"]').text('');
        $('button[name="button_form_modal"]').append('<span class=\"spinner-grow spinner-grow-sm mb-1\"></span> Mohon tunggu...');
        $('button[type="reset"]').hide();
    } else {
        $('button[name="button_form_modal"]').removeAttr('disabled');
        $('button[name="button_form_modal"]').removeAttr('span');
        $('button[name="button_form_modal"]').text('');
        $('button[name="button_form_modal"]').append('<i class=\"fa fa-check\"></i> Submit');
        $('button[type="reset"]').show();
    }
}
$(function() {
    $("#main-form-modal").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function() {
                reset_button_modal(0);
                swal.fire({
                    title: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    didOpen: function () {
                      swal.showLoading()
                    }
                })
                $(document).find('small.text-danger').text('');
                $(document).find('input').removeClass('is-invalid');
                $(document).find('select').removeClass('is-invalid');
                $(document).find('textarea').removeClass('is-invalid');
            },
            success: function(data) {
                swal.close()
                reset_button_modal(1);
                if (data.status == false) {
                    if (data.type == 'validation') {
                        $.each(data.msg, function(key, val) {
                            $("input[name=" + key.replace(".", "_") + "]").addClass('is-invalid').focus();
                            $("select[name=" + key.replace(".", "_") + "]").focus();
                            // $("option").addClass('is-invalid').focus();
                            $("textarea[name=" + key.replace(".", "_") + "]").addClass('is-invalid').focus();
                            $('small.' + key.replace(".", "_") +'-invalid').text(val[0]).focus();
                        });
                    }
                    if (data.type == 'alert') {
                        swal.fire("Gagal!", data.msg, "error");
                    }
                } else {
                    reset_button_modal(1);
                    $("#modal-form").modal('hide');
                    Toast.fire("Berhasil!", data.msg, "success");
                    $('#datatable').DataTable().draw();
                }
            },
            error: function() {
                swal.close()
                reset_button_modal(1);
                $(document).find('small.text-danger').text('');
                $(document).find('input').removeClass('is-invalid');
                $(document).find('select').removeClass('is-invalid');
                $(document).find('textarea').removeClass('is-invalid');
                swal.fire("Gagal!", "Terjadi kesalahan.", "error");
            },
        });
    });
});
function modal(type, title = 'Data', url) {
    if (type == 'add') {
        $('#modal-title').html('<i class="fa fa-plus-square"></i> Tambah ' + title );
    } else if (type == 'send') {
        $('#modal-title').html('<i class="fa fa-plus-square"></i> Kirim ' + title );
    } else if (type == 'edit') {
        $('#modal-title').html('<i class="fa fa-edit"></i> Edit ' + title );
    } else if (type == 'reply') {
        $('#modal-title').html('<i class="fa fa-edit"></i> Balas ' + title );
    } else if (type == 'delete') {
        $('#modal-title').html('<i class="fa fa-trash"></i> Delete ' + title );
    } else if (type == 'detail') {
        $('#modal-title').html('<i class="fa fa-search"></i> Detail ' + title );
    } else if (type == 'filter') {
        $('#modal-title').html('<i class="fa fa-filter"></i> Filter ' + title );
    } else if (type == 'confirm') {
        $('#modal-title').html('<i class="fa fa-check"></i> Konfirmasi ' + title );
    } else if (type == 'search-invoice') {
        $('#modal-title').html('<i class="mdi mdi-feature-search-outline"></i> Cari Pesanan');
    } else if (type == 'login') {
        $('#modal-title').html('<i class="mdi mdi-login"></i> Login');
    } else if (type == 'register') {
        $('#modal-title').html('<i class="mdi mdi-account-plus-outline"></i> Register');
    } else if (type == 'forgot-password') {
        $('#modal-title').html('<i class="mdi mdi-feature-search-outline"></i> Cari Pesanan');
    } else {
        $('#modal-title').html('Empty');
    }
    if (type == 'add' || type == 'edit' || type == 'reply' || type == 'send') {
        $('#modal-footer').addClass('hidden');
    } else {
        $('#modal-footer').removeClass('hidden');
    }
    $.ajax({
        type: "GET",
        url: url,
        beforeSend: function() {
            swal.fire({
                title: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: function () {
                  swal.showLoading()
                }
            })
            $('#modal-detail-body').html('<div class="text-center"><div class="loader-box"><div class="loader-3"></div></div></div>');
        },
        success: function(result) {
            $('#modal-form').modal({backdrop: 'static', keyboard: false});
            $('#modal-form').modal('show');
            if (result.type == 'html') {
                $('#modal-detail-body').html(result.data);
            } else {
                $('#modal-detail-body').html(result);
            }
            swal.close();
        },
        error: function() {
            $('#modal-form').modal('hide');
            swal.fire("Gagal!", "Terjadi kesalahan.", "error");
        }
    });
    $('#modal-detail').modal();
}
// $('.select2').select2();
function copy(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */
    document.execCommand("copy");
    swal.fire("Disalin!", "'" + copyText.value + "'.", "success");
}
function info(data) {
    swal.fire("Informasi!", data, "info");
}
function deleteData(elt, id, title, url, info = '') {
    swal.fire({
        title: "Apakah anda yakin?",
        html: 'Hapus Permanen <b style="font-weight: bold;">' + title + '</b>?' + info ,
        icon: "warning",
        showCancelButton: !0,
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Tidak, Batalkan!",
        confirmButtonClass: "btn btn-success",
        cancelButtonClass: "btn btn-danger",
    }).then(result => {
        if (result.value) {
            $.ajax({
                url: url,
                type: 'POST',
                data: '_method=DELETE',
                beforeSend: function() {
                    swal.fire({
                        title: 'Mohon Tunggu...',
                        allowOutsideClick: false,
                        didOpen: function () {
                          swal.showLoading()
                        },
                    })
                    $('#modal-detail-body').html('<div class="text-center"><div class="loader-box"><div class="loader-3"></div></div></div>');
                },
                error: function() {
                    swal.fire("Gagal", "Terjadi kesalahan.", "error");
                },
                success: function(data) {
                    if (data.result == false) {
                        swal.fire("Gagal", "Terjadi kesalahan.", "error");
                    } else {
                        $('#datatable').DataTable().draw('page');
                        swal.fire("Berhasil!", '<b style="font-weight: bold;">' + title + '</b> berhasil dihapus.', "success");
                    }
                }
            });
        } else {
            swal.fire("Dibatalkan", "Hapus data dibatalkan.", "error");
        }
    });
}
function switchStatus(elt, id, url) {
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
            swal.fire({
                title: 'Mohon Tunggu...',
                allowOutsideClick: false,
                didOpen: function () {
                  swal.showLoading()
                },
            })
        },
        error: function() {
            $('#datatable').DataTable().draw('page');
            Toast.fire("Gagal!", 'Terjadi kesalahan !.', "error");
        },
        success: function(data) {
            if (data.status == false) {
                $('#datatable').DataTable().draw('page');
                Toast.fire("Gagal!", data.msg, "error");
            } else {
                Toast.fire("Berhasil!", data.msg, "success");
                // if ($(elt).attr('value') == '1') {
                //     $("label[for="+$(elt).attr('id')+"]").text('Aktif');
                // } else {
                //     $("label[for="+$(elt).attr('id')+"]").text('Nonaktif');
                // }
                $('#datatable').DataTable().draw('page');
            }
        }
    });
}
