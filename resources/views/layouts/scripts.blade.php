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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>

{{-- Datatable --}}
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

<script>
    $(document).on('click', 'a', function(event) {
        event.preventDefault();
    });

    @if(Session::has('toastr-success'))
        toastr.success("{{ Session::get('toastr-success') }}")
    @endif

    @if(Session::has('toastr-error'))
        toastr.error("{{ Session::get('toastr-error') }}")
    @endif

    function deleteItem(id,name,) {
        Swal.fire({
            title: 'Are you sure want to delete ' + name + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $(id).submit();
                Swal.fire(
                    'Deleted!',
                    'success'
                )
            }
        })
    }

</script>
