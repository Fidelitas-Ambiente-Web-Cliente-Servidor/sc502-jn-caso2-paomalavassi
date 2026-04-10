
$(function () {
	const urlBase = "index.php";
	const cuerpoTabla = $("#talleres-body");
	const mensaje = $("#mensaje");

	function mostrarMensaje(texto, ok) {
		mensaje.text(texto).removeClass("ok error").addClass(ok ? "ok" : "error").show();
	}

	function cargarTalleres() {
		$.ajax({
			url: urlBase + "?option=talleres_json",
			method: "GET",
			dataType: "json",
			success: function (data) {
				cuerpoTabla.html("");

				if (data.response !== "00") {
					cuerpoTabla.append('<tr><td colspan="5">No autorizado</td></tr>');
					return;
				}

				if (!data.talleres.length) {
					cuerpoTabla.append('<tr><td colspan="5">No hay talleres disponibles</td></tr>');
					return;
				}

				data.talleres.forEach(function (taller) {
					const cupo = Number(taller.cupo_disponible);
					const esAdmin = data.rol === "admin";
				let boton;

				if (esAdmin) {
					boton = '<span class="badge">Solo lectura</span>';
				} else if (taller.estado_solicitud === 'pendiente') {
					boton = '<span class="badge bg-warning">Solicitud pendiente</span>';
				} else if (taller.estado_solicitud === 'aprobada') {
					boton = '<span class="badge bg-success">Taller aprobado</span>';
				} else if (taller.estado_solicitud === 'rechazada') {
					boton = '<span class="badge bg-danger">Taller rechazado</span>';
				} else if (cupo > 0) {
					boton = '<button class="btn btn-primary btn-sm btn-solicitar" data-id="' + taller.id + '">Solicitar</button>';
				} else {
					boton = '<span class="badge bg-secondary">Agotado</span>';
				}

					cuerpoTabla.append(
						"<tr>" +
						"<td>" + taller.nombre + "</td>" +
						"<td>" + (taller.descripcion || "-") + "</td>" +
						"<td>" + taller.cupo_maximo + "</td>" +
						"<td>" + taller.cupo_disponible + "</td>" +
						"<td>" + boton + "</td>" +
						"</tr>"
					);
				});
			},
			error: function () {
				cuerpoTabla.html('<tr><td colspan="5">Error al cargar talleres</td></tr>');
			}
		});
	}

	$(document).on("click", ".btn-solicitar", function () {
		const tallerId = $(this).data("id");
		$.ajax({
			url: urlBase,
			method: "POST",
			dataType: "json",
			data: {
				option: "solicitar",
				taller_id: tallerId
			},
			success: function (data) {
				mostrarMensaje(data.message, data.response === "00");
				cargarTalleres();
			},
			error: function () {
				mostrarMensaje("Error de conexion", false);
			}
		});
	});

	cargarTalleres();
	setInterval(cargarTalleres, 1000);
});
