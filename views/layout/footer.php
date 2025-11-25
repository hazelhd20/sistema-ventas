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

    (function() {
        function getConfirmMessage(el) {
            if (el.dataset.confirm) return el.dataset.confirm;
            if (el.dataset.newState === '1' && el.dataset.confirmActivar) return el.dataset.confirmActivar;
            if (el.dataset.newState === '0' && el.dataset.confirmDesactivar) return el.dataset.confirmDesactivar;
            return '';
        }

        function setLoading(el, isLoading) {
            const target = el.tagName === 'FORM' ? el.querySelector('[data-ajax-toggle-trigger]') || el : el;
            if (!target) return;
            target.classList.toggle('opacity-60', isLoading);
            target.classList.toggle('pointer-events-none', isLoading);
        }

        function applyStateClasses(pill, isActive) {
            const activeClass = pill.dataset.classActivo || 'pill bg-green-pastel/70 text-gray-800';
            const inactiveClass = pill.dataset.classInactivo || 'pill bg-gray-200 text-gray-700';
            pill.className = isActive ? activeClass : inactiveClass;
            pill.textContent = isActive ? 'Activo' : 'Inactivo';
            pill.dataset.state = isActive ? '1' : '0';
        }

        function refreshToggleDatasets(target, isActive) {
            target.dataset.currentState = isActive ? '1' : '0';
            target.dataset.newState = isActive ? '0' : '1';
            if (target.dataset.urlActivar && target.dataset.urlDesactivar) {
                target.dataset.toggleUrl = isActive ? target.dataset.urlDesactivar : target.dataset.urlActivar;
            }
            if (target.dataset.confirmActivar && target.dataset.confirmDesactivar) {
                target.dataset.confirm = isActive ? target.dataset.confirmDesactivar : target.dataset.confirmActivar;
            }
        }

        function applyToggleUI(source, isActive) {
            const scope = source.closest('[data-estado-scope]') || source.closest('tr') || source.closest('div');
            const pill = scope ? scope.querySelector('[data-estado-pill]') : null;
            const target = source.tagName === 'FORM'
                ? source.querySelector('[data-ajax-toggle-trigger]') || source
                : source;

            if (pill) {
                applyStateClasses(pill, isActive);
            }

            if (target) {
                target.classList.toggle('text-red-700', isActive);
                target.classList.toggle('text-green-700', !isActive);
                const textSpan = target.querySelector('[data-toggle-text]');
                if (textSpan) {
                    textSpan.textContent = isActive ? 'Desactivar' : 'Activar';
                }
                const icon = target.querySelector('[data-lucide]');
                if (icon) {
                    icon.setAttribute('data-lucide', isActive ? 'ban' : 'check-circle');
                }
                refreshToggleDatasets(target, isActive);
            }

            refreshToggleDatasets(source, isActive);

            if (source.tagName === 'FORM') {
                const accionInput = source.querySelector('input[name="accion"]');
                if (accionInput) {
                    accionInput.value = isActive ? 'desactivar' : 'activar';
                }
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        async function sendToggle(el) {
            const url = el.dataset.toggleUrl || el.dataset.url || el.getAttribute('href') || el.action;
            const rawMethod = el.dataset.method || el.getAttribute('method') || 'GET';
            const method = rawMethod.toUpperCase();
            const headers = {'X-Requested-With': 'XMLHttpRequest'};
            let body;

            if (method !== 'GET' && method !== 'HEAD') {
                if (el.dataset.body) {
                    body = new URLSearchParams(el.dataset.body);
                } else if (el.tagName === 'FORM') {
                    body = new FormData(el);
                }
            }

            const response = await fetch(url, {method, headers, body});
            if (!response.ok) {
                throw new Error('Request failed');
            }
        }

        async function handleToggle(el, event) {
            if (event) event.preventDefault();
            const confirmMsg = getConfirmMessage(el);
            if (confirmMsg && !confirm(confirmMsg)) return;

            setLoading(el, true);
            try {
                await sendToggle(el);
                const isActive = (el.dataset.newState ?? el.dataset.state ?? '0') === '1';
                applyToggleUI(el, isActive);
            } catch (error) {
                alert('No se pudo actualizar el estado. Intente nuevamente.');
            } finally {
                setLoading(el, false);
            }
        }

        document.addEventListener('click', function(event) {
            const trigger = event.target.closest('[data-ajax-toggle]:not(form)');
            if (!trigger) return;
            handleToggle(trigger, event);
        });

        document.addEventListener('submit', function(event) {
            const form = event.target.closest('form[data-ajax-toggle]');
            if (!form) return;
            handleToggle(form, event);
        });
    })();
</script>
</body>
</html>
