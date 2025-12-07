Archivo para dejar un registro escrito de las modificaciones

[base.html.twig](templates/base.html.twig)
Se agregaron nuevas clases de estilos para modificar logo, mensaje de bienvenida y vista de las noticias.
Se modifico el codigo donde esta escrito el logo para adaptarlo

[paginaPrincipal.html.twig](templates/pagina_principal/paginaPrincipal.html.twig)
Se agrega las clases definidas en base a cada noticia y se modifico la llamada a la imagen

[public](public/images/)
Se agrego una carpetas images y se subieron 2 imagenes de ejemplo

[Noticia.php](src/Entity/Noticia.php)
Se agrego un nuevo atributo imagen, con getter y setter.

[NoticiaFixtures.php](src/DataFixtures/NoticiaFixtures.php)
Se agrego una llamada al metodo SetImagen para cada noticia con el nombre correspondiente de la imagen

[noticia.hmtl.twig](templates/pagina_noticia/noticia.html.twig)
Se agrego la llamada a la imagen

[migrations](migrations/Version20251207220753.php)
Ultima migracion creada funcional para agregar la tabla imagen y su contenido
