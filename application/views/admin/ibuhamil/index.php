<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header bg-light">
						<h3 class="card-title"><i class="fa fa-list text-blue"></i> Data Ibu Hamil</h3>
						<div class="text-right">
							<button type="button" class="btn btn-sm btn-outline-primary" onclick="add_ibuhamil()"
								title="Add Data"><i class="fas fa-plus"></i> Add</button>
						</div>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
						<table id="tb_ibuhamil" class="table table-bordered table-striped table-hover">
							<thead>
								<tr class="bg-info">
									<th>NIK</th>
									<th>Nama Ibu Hamil</th>
									<th>Usia</th>
									<th>Alamat</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<!-- /.card-body -->
				</div>
				<!-- /.card -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>

<!-- Modal Hapus-->
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Konfirmasi</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<input type="hidden" name="idhapus" id="idhapus">
				<p>Apakah anda yakin ingin menghapus submenu <strong class="text-konfirmasi"> </strong> ?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success btn-xs" data-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-danger btn-xs" id="konfirmasi">Hapus</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal-default">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title ">View Ibu Hamil</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body text-center" id="md_def">
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script type="text/javascript">
	var save_method; //for save method string
	var table;

	$(document).ready(function () {

		//datatables
		table = $("#tb_ibuhamil").DataTable({
			"responsive": true,
			"autoWidth": false,
			"language": {
				"sEmptyTable": "Data Ibu Hamil Kosong"
			},
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.

			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "<?php echo site_url('ibuhamil/ajax_list')?>",
				"type": "POST"
			},
			//Set column definition initialisation properties.
			"columnDefs": [{
					"targets": [-1], //last column
					"render": function (data, type, row) {
						return "<a class=\"btn btn-xs btn-outline-info\" href=\"javascript:void(0)\" title=\"View\" onclick=\"vibuhamil(" +
							row[7] +
							")\"><i class=\"fas fa-eye\"></i></a> <a class=\"btn btn-xs btn-outline-primary\" href=\"javascript:void(0)\" title=\"Edit\" onclick=\"edit_ibuhamil(" +
							row[7] +
							")\"><i class=\"fas fa-edit\"></i></a> <a class=\"btn btn-xs btn-outline-danger\" href=\"javascript:void(0)\" title=\"Delete\" nama=" +
							row[1] + "  onclick=\"delibuhamil(" + row[7] +
							")\"><i class=\"fas fa-trash\"></i></a> ";
					},

					"orderable": false, //set not orderable
				},

			],
		});

		//set input/textarea/select event when change value, remove class error and remove text help block 
		$("input").change(function () {
			$(this).parent().parent().removeClass('has-error');
			$(this).next().empty();
			$(this).removeClass('is-invalid');
		});
		$("textarea").change(function () {
			$(this).parent().parent().removeClass('has-error');
			$(this).next().empty();
			$(this).removeClass('is-invalid');
		});
		$("select").change(function () {
			$(this).parent().parent().removeClass('has-error');
			$(this).next().empty();
			$(this).removeClass('is-invalid');
		});

	});

	function reload_table() {
		table.ajax.reload(null, false); //reload datatable ajax 
	}

	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 3000
	});

	//view
	function vibuhamil(id) {
		$('.modal-title').text('View Ibu Hamil');
		$("#modal-default").modal('show');
		$.ajax({
			url: '<?php echo base_url('ibuhamil/viewibuhamil '); ?>',
			type: 'post',
			data: 'table=tb_ibuhamil&id=' + id,
			success: function (respon) {
				$("#md_def").html(respon);
			}
		})
	}


	//delete
	function delibuhamil(id) {

		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					url: "<?php echo site_url('ibuhamil/delete');?>",
					type: "POST",
					data: "id_ibuhamil=" + id,
					cache: false,
					dataType: 'json',
					success: function (respone) {
						if (respone.status == true) {
							reload_table();
							Swal.fire(
								'Deleted!',
								'Your file has been deleted.',
								'success'
							);
						} else {
							Toast.fire({
								icon: 'error',
								title: 'Delete Error!!.'
							});
						}
					}
				});
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				Swal(
					'Cancelled',
					'Your imaginary file is safe :)',
					'error'
				)
			}
		})
	}

	function add_ibuhamil() {
		save_method = 'add';
		$('#form')[0].reset(); // reset form on modals
		$('.form-group').removeClass('has-error'); // clear error class
		$('.help-block').empty(); // clear error string
		$('#modal_form').modal({
			backdrop: 'static',
			keyboard: false
		}); // show bootstrap modal
		$('.modal-title').text('Add Ibu Hamil'); // Set Title to Bootstrap modal title
	}

	function edit_ibuhamil(id) {
		save_method = 'update';
		$('#form')[0].reset(); // reset form on modals
		$('.form-group').removeClass('has-error'); // clear error class
		$('.help-block').empty(); // clear error string

		//Ajax Load data from ajax
		$.ajax({
			url: "<?php echo site_url('ibuhamil/edit_ibuhamil')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function (data) {

				$('[name="id_ibuhamil"]').val(data.id_ibuhamil);
				$('[name="nik_ibuhamil"]').val(data.nik_ibuhamil);
				$('[name="nama_ibuhamil"]').val(data.nama_ibuhamil);
				$('[name="tempat_lahir"]').val(data.tempat_lahir);
				$('[name="tanggal_lahir"]').val(data.tanggal_lahir);
				$('[name="usia"]').val(data.usia);
				// $('[name="nama_orangtua"]').val(data.nama_orangtua);
				$('[name="no_hp"]').val(data.no_hp);
				$('[name="alamat"]').val(data.alamat);
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Ibu Hamil'); // Set title to Bootstrap modal title

			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}

	//hanya bisa masukan angka
	function hanyaAngka(event) {
		var angka = (event.which) ? event.which : event.keyCode
		if (angka != 46 && angka > 31 && (angka < 48 || angka > 57))
			return false;
		return true;
	}

	function save() {
		$('#btnSave').text('saving...'); //change button text
		$('#btnSave').attr('disabled', true); //set button disable 
		if (save_method == 'add') {
			url = "<?php echo site_url('ibuhamil/insert')?>";
		} else {
			url = "<?php echo site_url('ibuhamil/update')?>";
		}

		// ajax adding data to database
		$.ajax({
			url: url,
			type: "POST",
			data: $('#form').serialize(),
			dataType: "JSON",
			success: function (data) {

				if (data.status) //if success close modal and reload ajax table
				{
					$('#modal_form').modal('hide');
					reload_table();
					Toast.fire({
						icon: 'success',
						title: 'Success!!.'
					});
				} else {
					for (var i = 0; i < data.inputerror.length; i++) {
						$('[name="' + data.inputerror[i] + '"]').addClass('is-invalid');
						$('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]).addClass(
							'invalid-feedback');
					}
				}
				$('#btnSave').text('save'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable 


			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Error adding / update data');
				$('#btnSave').text('save'); //change button text
				$('#btnSave').attr('disabled', false); //set button enable 

			}
		});
	}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content ">

			<div class="modal-header">
				<h3 class="modal-title">Person Form</h3>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>
			<div class="modal-body form">
				<form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id_ibuhamil" />
					<div class="card-body">
						<div class="form-group row ">
							<label for="nama" class="col-sm-3 col-form-label">NIK</label>
							<div class="col-sm-9 kosong">
								<input onkeypress="return hanyaAngka(event)" type="text" class="form-control"
									name="nik_ibuhamil" id="nik_ibuhamil" placeholder="Masukan NIK">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group row ">
							<label for="nama_ibuhamil" class="col-sm-3 col-form-label">Nama Ibu Hamil</label>
							<div class="col-sm-9 kosong">
								<input type="text" class="form-control" name="nama_ibuhamil" id="nama_ibuhamil"
									placeholder="Nama Lengkap">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group row ">
							<label for="nama_owner" class="col-sm-3 col-form-label">Tempat Lahir</label>
							<div class="col-sm-9 kosong">
								<input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir"
									placeholder="Masukan Tempat Lahir">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group row ">
							<label for="alamat" class="col-sm-3 col-form-label">Tanggal Lahir</label>
							<div class="col-sm-9 kosong">
								<input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir"
									placeholder="Masukan Tanggal Lahir">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group row ">
							<label for="alamat" class="col-sm-3 col-form-label">Usia</label>
							<div class="col-sm-9 kosong">
								<input onkeypress="return hanyaAngka(event)" type="text" class="form-control"
									name="usia" id="usia" placeholder="Masukan Usia">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group row ">
							<label for="alamat" class="col-sm-3 col-form-label">No HP</label>
							<div class="col-sm-9 kosong">
								<input onkeypress="return hanyaAngka(event)" type="text" class="form-control"
									name="no_hp" id="no_hp" placeholder="Masukan No HP">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group row ">
							<label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
							<div class="col-sm-9 kosong">
								<input type="text" class="form-control" name="alamat" id="alamat"
									placeholder="Masukan Alamat Lengkap">
								<span class="help-block"></span>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
