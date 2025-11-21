        </main>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    if (window.lucide) {
        lucide.createIcons();
    }

    function confirmarEliminacion(mensaje = 'Esta seguro de eliminar este registro?') {
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
