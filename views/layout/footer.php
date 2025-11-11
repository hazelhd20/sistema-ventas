        </main>
    </div>

    <script>
        // Funciones JavaScript comunes
        function confirmarEliminacion(mensaje = '¿Está seguro de eliminar este registro?') {
            return confirm(mensaje);
        }

        function formatearMoneda(numero) {
            return new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN'
            }).format(numero);
        }
    </script>
</body>
</html>

