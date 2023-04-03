<!-- jquery latest version -->
<script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>

<!-- modernizr css -->
<script src="{{ asset('assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>

<!-- bootstrap 4 js -->
<script src="{{ asset('assets/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slicknav.min.js') }}"></script>

<!-- others plugins -->
<script src="{{ asset('assets/js/plugins.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('toastr/toastr.min.js') }}"></script>
<script src="{{ asset('mask/jquery.mask.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
    integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>

{{-- DATA TABLE--}}
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        iconColor: 'white',
        customClass: {
            popup: 'colored-toast'
        },
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    @if(Session::has('toastr-success'))
        Toast.fire({
            icon: 'success',
            title: '{{ Session::get('toastr-success') }}'
        })
    @endif

    @if(Session::has('toastr-error'))
        Toast.fire({
            icon: 'error',
            title: '{{ Session::get('toastr-error') }}'
        })
    @endif

    @if(Session::has('swal-error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ Session::get('swal-error') }}",
        })
    @endif

    @if(Session::has('swal-warning'))
        Swal.fire({
            icon: 'warning',
            text: "{{ Session::get('swal-warning') }}",
        })
    @endif

    function deleteItem(form_id,name) {
        Swal.fire({
            title: 'Are you sure want to delete ' + name + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $(form_id).submit();
                Swal.fire(
                    'Deleted!',
                    'success'
                )
            }
        })
    }
</script>
