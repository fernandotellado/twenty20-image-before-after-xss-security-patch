# Parche temporal de seguridad XSS para Twenty20

Un pequeño plugin para WordPress que mitiga la vulnerabilidad de **Cross-Site Scripting (XSS) almacenado** detectada en el plugin **Twenty20 Image Before-After** (reportada en WPScan: [e54804c7-68a9-4c4c-94f9-1c3c9b97e8ca](https://wpscan.com/vulnerability/e54804c7-68a9-4c4c-94f9-1c3c9b97e8ca/)).

## Resumen

La vulnerabilidad permite que un usuario con acceso a crear o editar contenido (por ejemplo, un autor o colaborador) pueda inyectar código JavaScript malicioso a través de los atributos del shortcode `[twenty20]`.  
Como estos atributos no estaban correctamente saneados ni escapados, cualquier código insertado en parámetros como `before`, `after`, `img1` o `img2` podía almacenarse y ejecutarse en el navegador de cualquier visitante que viera la entrada afectada.

Este plugin parchea el problema saneando todos los atributos del shortcode antes de pasarlos a la función original de salida de Twenty20.

## Cómo funciona

- Se engancha en el proceso `do_shortcode_tag` del shortcode `[twenty20]`.  
- Valida y sanea todos los atributos:
  - `img1` y `img2`: solo se permiten IDs numéricos o URLs válidas.  
  - `offset`: se convierte a número decimal (rango 0–1).  
  - `orientation`: solo se aceptan los valores `'horizontal'` o `'vertical'`.  
  - `before_label`, `after_label` y otros textos se limpian con `sanitize_text_field()`.  
- Pasa los atributos ya filtrados a la función original de Twenty20, conservando toda la funcionalidad.

De esta forma puedes seguir usando el shortcode con normalidad, pero evitando cualquier intento de inyección de JavaScript.

## Ejemplo

Uso seguro del shortcode:

```php
[twenty20 img1="123" img2="456" before="Antes" after="Después" offset="0.5"]
```

Ejemplo de inyección maliciosa neutralizada:

```php
[twenty20 img1="javascript:alert(1)" img2="..." before="<script>alert(1)</script>"]
```

Este código será saneado y no se ejecutará.

## Compatibilidad

- Probado con **Twenty20 versión 2.0.4**  
- Compatible con **WordPress 6.x+**  
- Requiere **PHP 7.4 o superior**

## Notas

Este es un **parche temporal** destinado a proteger tu sitio hasta que el plugin oficial Twenty20 publique una actualización con la corrección de seguridad.  
Cuando el plugin oficial se haya actualizado, basta con desactivar y eliminar este parche.

---

**Autor:** Fernando Tellado  
**Web:** [https://servicios.ayudawp.com](https://servicios.ayudawp.com)  
**Licencia:** GPL-3.0-or-later

