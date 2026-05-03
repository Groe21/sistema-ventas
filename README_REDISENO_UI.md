# Plan de Rediseño UI (Paso a Paso)

Este documento sirve para mejorar el aspecto visual del sistema sin afectar la logica de negocio.

## Objetivo

Unificar la apariencia del panel interno con la identidad visual de la landing, manteniendo:

- misma funcionalidad
- mismas rutas
- mismos controladores
- mismos flujos de datos

## Regla principal (No romper nada)

Todos los cambios iniciales seran solo de presentacion:

- CSS / estilos
- variables de color
- tipografia
- espaciados, bordes y sombras

No se modifican consultas, modelos, controladores ni migraciones para este frente.

## Paleta y estilo (base inicial)

> Nota: Esta base se puede ajustar antes de implementar.

- Primario: `#5B5BD6`
- Primario hover: `#4A4AC4`
- Fondo general: `#F5F7FB`
- Superficie (cards): `#FFFFFF`
- Texto principal: `#1F2937`
- Texto secundario: `#6B7280`
- Borde suave: `#E5E7EB`
- Exito: `#10B981`
- Alerta: `#F59E0B`
- Error: `#EF4444`

## Fases de trabajo

### Fase 0 - Base y control visual
- [ ] Crear respaldos visuales (capturas antes/despues)
- [ ] Definir alcance de pantallas criticas:
  - [ ] Dashboard
  - [ ] POS
  - [ ] Ventas
  - [ ] Caja
  - [ ] Productos
  - [ ] Formularios (crear/editar)
  - [ ] Login / Register

### Fase 1 - Tokens de diseno (segura)
- [x] Crear variables CSS globales (`:root`)
- [x] Aplicar colores base de fondo/texto/superficie
- [x] Definir tipografia base y escala de texto
- [x] Definir radios y sombras estandar

### Fase 2 - Componentes principales
- [x] Sidebar (estado normal/activo/hover)
- [x] Topbar
- [x] Cards (encabezado, cuerpo, espacio interno)
- [x] Botones primario/secundario/outline

### Fase 3 - Formularios y tablas
- [x] Inputs, selects, textareas
- [x] Estados focus/disabled/error
- [x] Tablas (cabecera, zebra, hover, densidad)
- [x] Badges y alertas

### Fase 4 - Pulido visual
- [x] Consistencia de iconos y espaciado
- [x] Contraste y legibilidad
- [x] Ajustes responsive en tablet/movil
- [x] Revision final de coherencia con landing

### Fase 5 - Verificacion final
- [ ] QA visual por modulo
- [ ] QA funcional (confirmar que no se altero logica)
- [ ] Aprobacion final

## Bitacora de cambios

Usar este formato en cada avance:

### Cambio N - (fecha)
- Estado: [ ] Pendiente / [ ] En progreso / [ ] Completado
- Modulos tocados:
- Archivos modificados:
- Objetivo del cambio:
- Resultado visual esperado:
- Riesgo funcional: Bajo / Medio / Alto
- Verificacion realizada:
- Notas:

### Cambio 1 - (2026-04-29)
- Estado: [ ] Pendiente / [ ] En progreso / [x] Completado
- Modulos tocados: Layout base del panel (navbar, sidebar, cards, botones, tablas e inputs)
- Archivos modificados: `resources/views/layouts/app.blade.php`
- Objetivo del cambio: Definir tokens visuales globales y aplicar una base mas amigable, moderna y consistente con la landing.
- Resultado visual esperado: Mejor contraste, paleta coherente, tipografia mas limpia y componentes base con mejor jerarquia visual.
- Riesgo funcional: Bajo
- Verificacion realizada: Actualizacion solo de estilos en `style` del layout, sin cambios en rutas, controladores, modelos, migraciones ni JS de comportamiento.
- Notas: Se uso paleta base (`#5B5BD6`, `#F5F7FB`, `#1F2937`, `#E5E7EB`) y se dejo lista la base para continuar con Fase 2.

### Cambio 2 - (2026-04-29)
- Estado: [ ] Pendiente / [ ] En progreso / [x] Completado
- Modulos tocados: Componentes principales del panel (topbar, sidebar, cards y botones)
- Archivos modificados: `resources/views/layouts/app.blade.php`
- Objetivo del cambio: Refinar la identidad visual en elementos de uso diario para que el panel se vea mas moderno y consistente con la marca.
- Resultado visual esperado: Navegacion mas clara, estados hover/activo mejor definidos, cards con mejor profundidad y botones con jerarquia visual mas amigable.
- Riesgo funcional: Bajo
- Verificacion realizada: Se modificaron estilos y microinteracciones visuales (hover/focus/active), sin cambios de estructura HTML funcional ni logica JS/PHP.
- Notas: Se agregaron estilos para dropdown, hover en tablas y detalles de espaciado/animacion ligera.

### Cambio 3 - (2026-04-29)
- Estado: [ ] Pendiente / [ ] En progreso / [x] Completado
- Modulos tocados: Formularios, tablas, badges y alertas del panel
- Archivos modificados: `resources/views/layouts/app.blade.php`
- Objetivo del cambio: Mejorar legibilidad y consistencia visual en los componentes de operacion diaria (captura y consulta de datos).
- Resultado visual esperado: Inputs mas claros, estados de validacion/focus/disabled visibles, tablas mas escaneables y alertas/badges con mejor jerarquia.
- Riesgo funcional: Bajo
- Verificacion realizada: Cambios globales de CSS sobre clases Bootstrap (`.form-control`, `.form-select`, `.table`, `.badge`, `.alert`) sin modificar flujos ni codigo backend.
- Notas: Se agregaron tokens de estado (`success`, `warning`, `danger`, `info`) para estandarizar colores en todo el panel.

### Cambio 4 - (2026-04-29)
- Estado: [ ] Pendiente / [ ] En progreso / [x] Completado
- Modulos tocados: Pulido general de layout, tipografia y responsive (topbar, sidebar, contenido, cards y tablas)
- Archivos modificados: `resources/views/layouts/app.blade.php`
- Objetivo del cambio: Afinar la experiencia visual final para que el panel se vea equilibrado, legible y consistente con la identidad de la landing.
- Resultado visual esperado: Mejor jerarquia tipografica, espaciado mas uniforme, mejor lectura en desktop y mejor adaptacion en tablet/movil.
- Riesgo funcional: Bajo
- Verificacion realizada: Ajustes exclusivos de CSS (sin alterar rutas, controladores, consultas ni eventos JS/PHP).
- Notas: Se mejoraron detalles de dropdowns, scroll visual del sidebar, densidad de tablas y comportamiento de topbar en pantallas pequenas.

---

## Primer cambio sugerido (siguiente paso)

Implementar **Fase 1**:

1. Crear variables globales de color/espaciado/tipografia.
2. Aplicarlas al layout base (body, cards, titulos, texto base).
3. Revisar dashboard para validar que solo cambia el look.

> Cuando completemos cada paso, iremos marcando este README.
