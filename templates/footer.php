
</main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
       
        <!-- Eliminar -->
        <script> 
        function borrar(id) {

            Swal.fire({ title: '¿Desea borrar el registro?'
                , showCancelButton: true, 
                confirmButtonText: 'Si, Borrar' }).
                then((result) => { 
                if (result.isConfirmed)
                { window.location="index.php?txtID=" + id; 
                } 
            })
            }
        </script>
 
      <!-- jquery -->
        <script>
        $(document).ready(function () {
            $('table').DataTable({
                "pageLength": 3,
                lengthMenu: [
                    [3, 10, 25, 50],
                    [3, 10, 25, 50]
                ],
                "language": {
                    "url": "http://cdn.datatables.net/plug-ins/1.13./i18n/Spanish.json"
                }
            });
        });
    </script>
</body>

</html>