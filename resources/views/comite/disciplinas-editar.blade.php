@extends('comite.layouts.app')

@section('content')
    <div class="dashboard-content">
        <!-- Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-trophy"></i> Editar Disciplina</h1>
                <p>Modifica los datos de la disciplina</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('comite.disciplinas') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Volver a Disciplinas
                </a>
            </div>
        </div>

        <!-- Mostrar mensajes de éxito/error -->
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <div class="form-section">
            <form id="disciplineForm" class="discipline-form" method="POST"
                action="{{ route('comite.disciplinas-update', $disciplina->id_disciplina) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <!-- Información Básica -->
                    <div class="form-card">
                        <div class="card-header">
                            <h3><i class="fas fa-info-circle"></i> Información Básica</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label required">
                                    <i class="fas fa-heading"></i>
                                    Nombre de la Disciplina
                                </label>
                                <input type="text" class="form-control" name="nombre"
                                    value="{{ old('nombre', $disciplina->nombre) }}" required
                                    placeholder="Ej: Fútbol Varonil">
                                @error('nombre')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label required">
                                    <i class="fas fa-tag"></i>
                                    Categoría
                                </label>
                                <select class="form-control" name="categoria" required>
                                    <option value="">Seleccionar categoría</option>
                                    <option value="Deporte"
                                        {{ old('categoria', $disciplina->categoria) == 'Deporte' ? 'selected' : '' }}>
                                        Deportiva
                                    </option>
                                    <option value="Cultural"
                                        {{ old('categoria', $disciplina->categoria) == 'Cultural' ? 'selected' : '' }}>
                                        Cultural
                                    </option>
                                </select>
                                @error('categoria')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label required">
                                    <i class="fas fa-venus-mars"></i>
                                    Género
                                </label>
                                <select class="form-control" name="genero" required>
                                    <option value="">Seleccionar género</option>
                                    <option value="Varonil"
                                        {{ old('genero', $disciplina->genero) == 'Varonil' ? 'selected' : '' }}>
                                        Varonil
                                    </option>
                                    <option value="Femenil"
                                        {{ old('genero', $disciplina->genero) == 'Femenil' ? 'selected' : '' }}>
                                        Femenil
                                    </option>
                                    <option value="Mixto"
                                        {{ old('genero', $disciplina->genero) == 'Mixto' ? 'selected' : '' }}>
                                        Mixto
                                    </option>
                                </select>
                                @error('genero')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label required">
                                    <i class="fas fa-users"></i>
                                    Cupo Máximo
                                </label>
                                <input type="number" class="form-control" name="cupo_maximo"
                                    value="{{ old('cupo_maximo', $disciplina->cupo_maximo) }}" required min="1"
                                    placeholder="Ej: 100">
                                @error('cupo_maximo')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" class="checkbox-input" name="activa" value="1"
                                        {{ old('activa', $disciplina->activa) ? 'checked' : '' }}>
                                    <span class="checkbox-custom"></span>
                                    <span>Disciplina Activa</span>
                                </label>
                                <div class="checkbox-description">
                                    Los usuarios podrán inscribirse solo en disciplinas activas
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Adicional -->
                    <div class="form-card">
                        <div class="card-header">
                            <h3><i class="fas fa-align-left"></i> Información Adicional</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label required">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha de Inicio
                                </label>
                                <input type="date" class="form-control" name="fecha_inicio"
                                    value="{{ old('fecha_inicio', $disciplina->fecha_inicio ? $disciplina->fecha_inicio->format('Y-m-d') : '') }}"
                                    required min="{{ date('Y-m-d') }}">
                                @error('fecha_inicio')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label required">
                                    <i class="fas fa-calendar-times"></i>
                                    Fecha de Fin
                                </label>
                                <input type="date" class="form-control" name="fecha_fin"
                                    value="{{ old('fecha_fin', $disciplina->fecha_fin ? $disciplina->fecha_fin->format('Y-m-d') : '') }}"
                                    required>
                                @error('fecha_fin')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label required">
                                    <i class="fas fa-align-left"></i>
                                    Descripción
                                </label>
                                <textarea class="form-control" name="descripcion" rows="4" placeholder="Descripción detallada de la disciplina..."
                                    required>{{ old('descripcion', $disciplina->descripcion) }}</textarea>
                                @error('descripcion')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group full-width">
                                <label class="form-label">
                                    <i class="fas fa-sticky-note"></i>
                                    Instrucciones Especiales
                                </label>
                                <textarea class="form-control" name="instrucciones" rows="3"
                                    placeholder="Instrucciones, requisitos o información adicional para los participantes...">{{ old('instrucciones', $disciplina->instrucciones) }}</textarea>
                                @error('instrucciones')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones del Formulario -->
                <div class="form-actions">
                    <a href="{{ route('comite.disciplinas') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Actualizar Disciplina
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .dashboard-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
        }

        .header-content h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-content p {
            color: var(--text-secondary);
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        /* Alertas */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
            color: var(--danger);
        }

        .alert-danger ul {
            margin: 0.5rem 0 0 0;
            padding-left: 1rem;
        }

        .alert-danger li {
            margin-bottom: 0.25rem;
        }

        /* Sección del Formulario */
        .form-section {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            padding: 2rem;
        }

        /* Tarjetas del Formulario */
        .form-card {
            background: var(--bg-light);
            border-radius: 12px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .form-card .card-header {
            padding: 1.25rem 1.5rem;
            background: white;
            border-bottom: 1px solid var(--border-color);
        }

        .form-card .card-header h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            color: var(--text-primary);
            margin: 0;
        }

        .form-card .card-body {
            padding: 1.5rem;
        }

        /* Grupos de Formulario */
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-label.required::after {
            content: '*';
            color: var(--danger);
            margin-left: 0.25rem;
        }

        .form-label i {
            color: var(--secondary-color);
            width: 16px;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(0, 170, 139, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .error-message {
            color: var(--danger);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Checkbox Personalizado */
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .checkbox-input {
            display: none;
        }

        .checkbox-custom {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .checkbox-input:checked+.checkbox-custom {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .checkbox-input:checked+.checkbox-custom::after {
            content: '✓';
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .checkbox-description {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-left: 2rem;
            margin-top: 0.25rem;
        }

        /* Acciones del Formulario */
        .form-actions {
            padding: 1.5rem 2rem;
            background: var(--bg-light);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        /* Botones */
        .btn-primary,
        .btn-secondary {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            border: none;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary {
            background: var(--bg-light);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-content {
                padding: 0.5rem;
            }

            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .header-actions {
                width: 100%;
                justify-content: center;
            }

            .form-grid {
                padding: 1rem;
                gap: 1rem;
            }

            .form-actions {
                flex-direction: column;
            }
        }
    </style>

    <script>
        // Validación de fechas en el cliente
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInicioInput = document.querySelector('input[name="fecha_inicio"]');
            const fechaFinInput = document.querySelector('input[name="fecha_fin"]');

            // Establecer fecha mínima para fecha_fin basada en fecha_inicio
            fechaInicioInput.addEventListener('change', function() {
                if (this.value) {
                    const minDate = new Date(this.value);
                    minDate.setDate(minDate.getDate() + 1);
                    fechaFinInput.min = minDate.toISOString().split('T')[0];

                    // Si fecha_fin es anterior a la nueva fecha_inicio, limpiarla
                    if (fechaFinInput.value && fechaFinInput.value <= this.value) {
                        fechaFinInput.value = '';
                    }
                }
            });
        });
    </script>
@endsection
