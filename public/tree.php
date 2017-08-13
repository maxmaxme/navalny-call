<?
require_once '../config.php';
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Title</title>

	<style>
		/* общий стиль */
		body {font-family: Arial, Tahoma, sans-serif; margin: 2em; font-size: 62.5%;}
		p {
			font-size: 1.2em;
		}
		a {
			color: #0066cc;
			text-decoration: none;
			outline: none;
		}
		/*a:link {
		 color: #0066cc;
		}
		a:visited {color: #00cc63; }*/
		a:hover {
			text-decoration: underline;
		}
		a:active, a:focus {
			color: #666;
			background-color: #f4f4f4;
		}
		a.current {
			color: black;
			font-weight: bold;
			background-color: #f4f4f4;
		}

		/* Дерево многоуровневое
		-------------------------------- */
		#multi-derevo {
			width: 100%; /* блок под дерево */
			border: solid; /* границы блока */
			border-color: silver gray gray silver;
			border-width: 2px;
			padding: 0 0 1em 0; /* нижний отступ */
			font-size: 1.3em;
		}
		span { /* обертка пункта */
			text-decoration: none;
			display: block; /* растянем до правого края блока */
			margin: 0 0 0 1.2em;
			background-color: transparent;
			border: solid silver; /* цвет линий */
			border-width: 0 0 1px 1px; /* границы: низ и лево */
		}
		span a {/* тест элемента дерева */
			display: block;
			position: relative;
			top: .95em; /* смещаем узел на середину линии */
			background-color: #fff; /* закраска в цвет фона обязательна иначе будет видно линию */
			margin: 0 0 .2em .7em; /* делаем промежуток между узлами, отодвигаем левее  */
			padding: 0 0.3em; /* небольшой отступ от линии */
		}
		h4 {/* заголовок дерева */
			font-size: 1em;
			font-weight: bold;
			margin: 0;
			padding: 0 .25em;
			border-bottom: 1px solid silver;
		}
		h4 a {
			display: block;
		}
		ul, li {
			list-style-image:none;
			list-style-position:outside;
			list-style-type:none;
			margin:0;
			padding:0;
		}
		ul li {
			line-height: 1.2em;
		}
		ul li ul {
			/*display: none;*/ /* узлы свернуты */
		}
		ul li ul li {
			margin: 0 0 0 1.2em;
			border-left: 1px solid silver; /* цвет вертикальной линии между узлами */
		}
		li.last {/* последний узел, соединительную линию к след. узлу убираем */
			border: none;
		}
		.marker { /* маркер раскрытия списка в закрытом состоянии */
			border-color: transparent transparent transparent gray;
			border-style: solid;
			border-width: .25em 0 .25em .5em;
			margin: .35em .25em 0 0;
			float: left;
			width: 0px;
			height: 0px;
			line-height: 0px;
		}
		.marker.open {/* маркер раскрытия списка в открытом состоянии */
			border-color: gray transparent transparent transparent;
			border-width: .5em .25em 0 .25em;
		}
		/* IE 6 Fixup */
		* html #multi-derevo * { height: 1%;}
		* html .marker { border-style: dotted dotted dotted solid; }
		* html .marker.open { border-style: solid dotted dotted dotted; }
		#multi-derevo a {
			cursor: pointer !important;
		}
	</style>

</head>
<body>

<div id="multi-derevo">
	<h4><a>Звонок</a></h4>
	<?=getBranch(1)?>
</div>

<? // getBranch(1); ?>



<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script>
<script>
    /*<![CDATA[*/
    /*
	Построение дерева по готовому HTML списку.

	Выделяем узлы имющие поддеревья и добавляем у ним метку.
	Функция определяет поведение узлов дерева при клике на них.
	 - Изменяет состояние маркера раскрытия (открыт/закрыт).
	 - Узлы содержащие в себе другие узлы, по клику разворачиваются
	  или сворачиваются, в зависимости от текущего состояния.
	 - При переходе с одного узла на другой снимается выделение (.current)
	  и пеходит на выбранный узел.
	 - Определяет последний узел с поддеревом и скрывает соединительную
	  линию до следующего узла этого уровня.
	*/
    $(document).ready(function () {
        /* Расставляем маркеры на узлах, имющих внутри себя поддерево.
		  Выбираем элементы 'li' которые имеют вложенные 'ul', ставим для них
		  маркер, т.е. находим в этом 'li' вложенный тег 'a'
		  и в него дописываем маркер '<em class="marker"></em>'.
		  a:first используется, чтобы узлам ниже 1го уровня вложенности
		  маркеры не добавлялись повторно.
		*/
        $('#multi-derevo li:has("ul")').find('a:first').prepend('<em class="marker open"></em>');
		// вешаем событие на клик по ссылке
        $('#multi-derevo li span').click(function () {
            // снимаем выделение предыдущего узла
            $('a.current').removeClass('current');
            var a = $('a:first',this.parentNode);
            // Выделяем выбранный узел
            //было a.hasClass('current')?a.removeClass('current'):a.addClass('current');
            a.toggleClass('current');
            var li=$(this.parentNode);
            /* если это последний узел уровня, то соединительную линию к следующему
			  рисовать не нужно */
            if (!li.next().length) {
                /* берем корень разветвления <li>, в нем находим поддерево <ul>,
				 выбираем прямых потомков ul > li, назначаем им класс 'last' */
                li.find('ul:first > li').addClass('last');
            }
            // анимация раскрытия узла и изменение состояния маркера
            var ul=$('ul:first',this.parentNode);// Находим поддерево
            if (ul.length) {// поддерево есть
                ul.slideToggle(300); //свернуть или развернуть
                // Меняем сосотояние маркера на закрыто/открыто
                var em=$('em:first',this.parentNode);// this = 'li span'
                // было em.hasClass('open')?em.removeClass('open'):em.addClass('open');
                em.toggleClass('open');
            }
        });
    })
    /*]]>*/
</script>

</body>
</html>