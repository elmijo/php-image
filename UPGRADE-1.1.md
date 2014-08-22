UPGRADE FROM 1.0 to 1.1
=======================

### General

La unica diferencia es la forma de guardar la imagen que acabamos de manipular

#### Antes

```php
$image->saveAsJPEG("nueva_imagen.jpg");

$image->saveAsPNG("nueva_imagen.png");

$image->saveAsGIF("nueva_imagen.gif");

```

#### Ahora

```php
$image->saveImage("nueva_imagen.jpg");

$image->saveImage("nueva_imagen.png");

$image->saveImage("nueva_imagen.gif");

```