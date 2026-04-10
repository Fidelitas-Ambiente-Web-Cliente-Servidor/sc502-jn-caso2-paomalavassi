$(function () {
	const urlBase = "index.php";
	const cuerpo = $("#solicitudes-body");
	const mensaje = $("#mensaje");

	function mostrarMensaje(texto, ok) {
		mensaje.text(texto).removeClass("ok error").addClass(ok ? "ok" : "error").show();
	}

	function cargarSolicitudes() {
		$.ajax({
			url: urlBase + "?option=solicitudes_json",
			method: "GET",
			dataType: "json",
			success: function (data) {
				cuerpo.html("");

				if (data.response !== "00") {
					cuerpo.append('<tr><td colspan="6">No autorizado</td></tr>');
					return;
				}

				if (!data.solicitudes.length) {
					cuerpo.append('<tr><td colspan="6">No hay solicitudes pendientes</td></tr>');
					return;
				}

				data.solicitudes.forEach(function (s) {
					const acciones =
						'<button class="btn btn-success btn-sm btn-aprobar" data-id="' + s.id + '">Aprobar</button> ' +
						'<button class="btn btn-danger btn-sm btn-rechazar" data-id="' + s.id + '">Rechazar</button>';

					cuerpo.append(
						"<tr>" +
						"<td>" + s.id + "</td>" +
						"<td>" + s.taller + "</td>" +
						"<td>" + s.usuario_id + "</td>" +
						"<td>" + s.usuario + "</td>" +
						"<td>" + s.fecha_solicitud + "</td>" +
						"<td>" + acciones + "</td>" +
						"</tr>"
					);
				});
			},
			error: function () {
				cuerpo.html('<tr><td colspan="6">Error al cargar solicitudes</td></tr>');
			}
		});
	}

	$(document).on("click", ".btn-aprobar, .btn-rechazar", function () {
		const id = $(this).data("id");
		const opcion = $(this).hasClass("btn-aprobar") ? "aprobar" : "rechazar";

		$.ajax({
			url: urlBase,
			method: "POST",
			dataType: "json",
			data: {
				option: opcion,
				id_solicitud: id
			},
			success: function (data) {
				mostrarMensaje(data.message, data.response === "00");
				cargarSolicitudes();
			},
			error: function () {
				mostrarMensaje("Error de conexion", false);
			}
		});
	});

	cargarSolicitudes();
	setInterval(cargarSolicitudes, 1000);
});
