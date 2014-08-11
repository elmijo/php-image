PHP Image
=========

Una libreria para manipular imagenes con la extención GD de php. Esta libreria esta pensada para redimencionar, escalar, cortar y haver miniaturas de imagenes. hasta ahora solo soporta imagenes png, jpe, jpeg, jpg, gif y una vez definida la imagen puede ser guuardada como jpeg, png o gif.


Metodos
-------

#### setImage


> * **Descripción:** Permite definir la imagen atrabajar. esto en caso de que no se defina al momento de inicializar la clase
> * **Parametros:** 
>   * filename: *(requerido)* Ruta absoluta de la imagen que deseamos definir 


#### thumbnailImage

> * **Desciopción:** Permite hacer miniaturas de imagenes
> * **Parametros:** 
>   * width: *(requerido)* Ancho de la miniatura
>   * height: *(opcional)* Alto de la miniatura, si no se define su valor sera igual al del ancho
> * **Observación:** Si al momento de utilizar este metodo no se a definido la imagen no generara ningun resultado


#### scaleImage

> * **Desciopción:** Permite escalar una imagenes
> * **Parametros:** 
>   * width: *(requerido)* Ancho del que se desea escalar la imagen
>   * height: *(opcional)* Alto del que se desea escalar la imagen, si no se define su valor sera igual al del ancho
> * **Observación:** Si al momento de utilizar este metodo no se a definido la imagen no generara ningun resultado


#### resizeImage

> * **Desciopción:** Permite redimencionar una imagenes
> * **Parametros:** 
>   * width: *(requerido)* Ancho del que se quiere redimencionar la imagen
>   * height: *(requerido)* Alto del que se quiere redimencionar la imagen

#### cropImage

> * **Desciopción:** Permite recortar una imagenes
> * **Parametros:** 
>   * width: *(requerido)* Ancho del que se desea cortar la imagen
>   * height: *(requerido)* Alto del que se desea cortae la imagen
>   * x: *(requerido)* Posición en el eje X del corte
>   * y: *(requerido)* posición  en el eje Y del corte

#### saveAsJPEG

> * **Desciopción:** Permite guardar una imagen como JPEG
> * **Parametros:** 
>   * filename: *(requerido)* Ruta absoluta de la imagen a guardar
>   * quality: *(opcional)* calidad de la imagen a guardar, debe ser un valor entre 0 y 100, por defecto el valor es 90

#### saveAsPNG

> * **Desciopción:** Permite guardar una imagen como PNG
> * **Parametros:** 
>   * filename: *(requerido)* Ruta absoluta de la imagen a guardar
>   * quality: *(opcional)* calidad de la imagen a guardar, debe ser un valor entre 0 y 9, por defecto el valor es 9
>   * filters: *(opcional)* Permite activar o desactivar los filtros de las imagenes PNG, por defecto el valor es FALSE

#### saveAsGIF

> * **Desciopción:** Permite guardar una imagen como GIF
> * **Parametros:** 
>   * filename: *(requerido)* Ruta absoluta de la imagen a guardar

Otra caracteristica a resaltar es el soporte a los fondos transparentes, permitiendo asi aplicar cualquiera de las manipulaciones descritas sin que afecte a las imagenes con background transparentes.