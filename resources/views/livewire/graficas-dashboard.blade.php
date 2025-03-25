<div class="">
    <div class="flex flex-wrap items-center justify-center gap-4">
        <!-- Primer gráfico -->
        <div class="w-1/3 p-2">
            <canvas id="proyectosCompletadosChart"></canvas>
        </div>

        <!-- Segundo gráfico -->
        <div class="w-1/4 p-2">
            <p class="text-xs text-center text-slate-500">Tareas Completadas por proyectos</p>
            <canvas id="tareasCompletadasChart"></canvas>
        </div>

        <!-- Tercer gráfico -->
        <div class="w-1/3 p-2">
            <canvas id="usuariosConMasTareasChart"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('proyectosCompletadosChart').getContext('2d');

        // Datos desde Livewire (en formato JSON)
        var proyectosCompletados = @json($proyectosCompletados);

        // Extraemos los nombres de los proyectos y el progreso de cada proyecto
        var nombresProyectos = Object.values(proyectosCompletados).map(proyecto => Object.keys(proyecto)[0]); // Nombres de los proyectos
        var progresoPorProyecto = Object.values(proyectosCompletados).map(proyecto => proyecto[Object.keys(proyecto)[0]]); // Progreso de cada proyecto

        // Configuración del gráfico
        var chart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico (puedes cambiarlo a 'line' o 'pie' si prefieres otro tipo)
            data: {
                labels: nombresProyectos, // Nombres de los proyectos
                datasets: [{
                    label: 'Progreso de los Proyectos (%)',
                    data: progresoPorProyecto, // Progreso de los proyectos
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,  // Para que el progreso no se pase del 100%
                        title: {
                            display: true,
                            text: 'Progreso (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Proyectos'
                        }
                    }
                }
            }
        });
    </script>



<script>
    var ctx = document.getElementById('tareasCompletadasChart').getContext('2d');

    var tareasCompletadas = @json($tareasCompletadas);

    var nombresProyectos = Object.values(tareasCompletadas).map(proyecto => proyecto.nombre);
    var tareasCompletadasPorProyecto = Object.values(tareasCompletadas).map(proyecto => proyecto.totalPerProject);

    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: nombresProyectos,
            datasets: [{
                label: 'Tareas Completadas por proyectos',
                data: tareasCompletadasPorProyecto,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 154, 65, 0.2)'],
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,  // Hacer que el gráfico sea sensible al tamaño de la pantalla
            plugins: {
                legend: {
                    position: 'top',  // Ubicación de la leyenda
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            // Agregar el número de tareas completadas al tooltip
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' tareas';
                        }
                    }
                }
            }
        }
    });
</script>



<script>
    var ctx = document.getElementById('usuariosConMasTareasChart').getContext('2d');
    var usuariosConMasTareas = @json($usuariosConMasTareas); // Datos desde Livewire
    var nombresUsuarios = usuariosConMasTareas.map(user => user.name);
    var numTareasPorUsuario = usuariosConMasTareas.map(user => user.numTareas);

    var chart = new Chart(ctx, {
        type: 'line', // Puedes elegir otro tipo de gráfico
        data: {
            labels: nombresUsuarios, // Nombres de usuarios
            datasets: [{
                label: 'Top 10 Usuarios con mas tareas',
                data: numTareasPorUsuario,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


</div>
