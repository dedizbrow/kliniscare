$modalManageRuangan = "#modal-manage-ruangan";
$modalManageKelas = "#modal-manage-kelas";
$modalManageKategori = "#modal-manage-kategori";
$modalManageImport = "#modal-manage-import";
$tableData = $("#dataRuangan").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "50",
    ajax: {
        url: base_url('master-data/ruangan/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
			d.clinic_id=getSelectedClinic();
			if ($("#import_id").length > 0 && $("#import_id").val() != "") d.import_id = $("#import_id").val();
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    order: [[1,'desc']],
    columnDefs: [
        { targets: [0], width: '35px',className: 'text-center' },
		{ targets: [6], width: '90px',className: 'text-right'},
		{ targets: [8], width: '15px',className: 'text-center', orderable: false },
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
    },
})

$(document)
	.ready(function () {
		getActiveLang('master-data/ruangan');
	})
	.on("click", ".add-ruangan", function () {
		$modal_id=$modalManageRuangan;
		$modal_body=$($modal_id).find('.modal-body');
		
		$modal_body.find("input:text").val("");
		$modal_body.find("select").val('').trigger('change');
		$modal_body.find(".show-on-update").addClass('hide');
		$($modal_id).modal({
			effect: 'effect-slide-in-right',
			backdrop: 'static',
			keyboard: false,
			show: true
		})
	})
	.on("click",".link-edit-ruangan",function(){
		http_request('master-data/ruangan/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageRuangan;
			$modal_body = $($modal_id).find('.modal-body');

			$('#kelas').html('<option value="'+data.idKelas+'">'+data.namaKelas+'</option>').trigger('change');
			$('#kategori').html('<option value="'+data.idKategori+'">'+data.namaKategori+'</option>').trigger('change');

			$.each(data, function (key, val) {
				if (key == 'status') {
					$modal_body.find("[name='" + key + "']").removeAttr("checked");
					$modal_body.find("[name='" + key + "'][value='" + val + "']").attr("checked", "checked");
				} else{
					$modal_body.find("[name='"+ key +"']").val(val);
				}
			})
			$($modal_id).modal({
					effect: 'effect-slide-in-right',
					backdrop: 'static',
					keyboard: false,
					show: true
			})
		})
	})
	.on("submit","form[name='form-manage-ruangan']",function(e){
		e.preventDefault();
		$("#save-ruangan").attr('disabled', 'disabled');
		$form=$(this).closest('form');
		var data = $form.serialize();
		http_request('master-data/ruangan/save__','POST',data)
		.done(function(res){
			$($modalManageRuangan).modal('hide');
			$tableData.ajax.reload();
			Msg.success(res.message);
			$("#save-ruangan").removeAttr('disabled');
		})
			.fail(function () {
				$("#save-ruangan").removeAttr('disabled');
			})
			.always(function () {
			$("#save-ruangan").removeAttr('disabled');
		})
	})
	.on("click",".link-delete-ruangan",function(){
		hideMsg();
		var id=$(this).data('id');
		$that=$(this);
		bootbox.confirm({
			title: $lang.bootbox_title_confirmation,
			message: $lang.bootbox_message_confirm_remove,
			size: 'small',
			buttons: {
				confirm: {
					label: $lang.bootbox_btn_confirm,
					className: 'btn-success'
				},
				cancel: {
					label: $lang.bootbox_btn_no,
					className: 'btn-danger'
				}
			},
			callback: function (result) {
						if(result){
							http_request('master-data/ruangan/delete__/'+id,'DELETE',{})
							.done(function(res){
									Msg.success(res.message);
									$tableData.ajax.reload(null,false);
							})
						}
			}
		});
		})

			
		.on("click", ".add-kelas", function () {
			$($modalManageRuangan).modal('hide');
			$modal_id=$modalManageKelas;
			$modal_body=$($modal_id).find('.modal-body');
			
			$modal_body.find("input:text").val("");
			$modal_body.find(".show-on-update").addClass('hide');
			$($modal_id).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
			})
		})
			
		.on("submit","form[name='form-manage-kelas']",function(t){
			t.preventDefault();
			$("#save-kelas").attr('disabled', 'disabled');
			$form=$(this).closest('form');
			var data = $form.serialize();
			http_request('master-data/ruangan/save_kelas','POST',data)
			.done(function(res){
				$($modalManageKelas).modal('hide');
				$tableData.ajax.reload();
				Msg.success(res.message);
				$("#save-kelas").removeAttr('disabled');
			})
				.fail(function () {
					$("#save-kelas").removeAttr('disabled');
				})
				.always(function () {
				$("#save-kelas").removeAttr('disabled');
				$($modalManageRuangan).modal('show');
			})
		})
		
		.on("click", ".add-kategori", function () {
			$($modalManageRuangan).modal('hide');
			$modal_id=$modalManageKategori;
			$modal_body=$($modal_id).find('.modal-body');
			
			$modal_body.find("input:text").val("");
			$modal_body.find(".show-on-update").addClass('hide');
			$($modal_id).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
			})
		})
			
		.on("submit","form[name='form-manage-kategori']",function(t){
			t.preventDefault();
			$("#save-kategori").attr('disabled', 'disabled');
			$form=$(this).closest('form');
			var data = $form.serialize();
			http_request('master-data/ruangan/save_kategori','POST',data)
			.done(function(res){
				$($modalManageKategori).modal('hide');
				$tableData.ajax.reload();
				Msg.success(res.message);
				$("#save-kategori").removeAttr('disabled');
			})
				.fail(function () {
					$("#save-kategori").removeAttr('disabled');
				})
				.always(function () {
				$("#save-kategori").removeAttr('disabled');
				$($modalManageRuangan).modal('show');
			})
		})

		// control import
.on("click", ".import__", function () {
	var modal_id=$modalManageImport;
	var modal_body=$(modal_id).find('.modal-body');
	// $modal_body.find('input[name="user_id"]').val('');
	
	modal_body.find("input:text").val("");
	modal_body.find("textarea[name='alamat']").val("");
	$(".row-error .title").html('');
	$(".row-error .content").html('');
	// $modal_body.find("[name*='password']").attr('required');
	modal_body.find(".show-on-update").addClass('hide');
	$(modal_id).modal({
		effect: 'effect-slide-in-right',
		backdrop: 'static',
		keyboard: false,
		show: true
	})
})
.on("click", ".choose_file__", function () {
	$(this).closest('div').find('input:file').trigger('click');
})
.on("change", "input:file[name='file']", function () {
	$that = $(this);
	var btn_control = $(this).closest('div').find('.btn');
	btn_control.removeClass('btn-warning').addClass('btn-primary');
	let file = document.getElementById("file").files[0];
	if (file.name == "") return false;
	$that.closest('div').find('.filename').text(file.name);
})
.on("submit", "form[name='form-manage-import']", function (e) {
	$that = $(this);
	var btn_control = $(this).find('.btn.choose_file__');
	btn_control.removeClass('btn-warning').addClass('btn-primary');
	e.preventDefault();
	//$("#submit-import").attr('disabled', 'disabled');
	let file = document.getElementById("file").files[0];
	if (file === undefined) return Msg.error("Pastikan telah memilih file");
	$(".row-error .title").html('');
	$(".row-error .content").html('');
	bootbox.confirm({
		title: $lang.bootbox_title_confirmation,
		message: $lang.bootbox_message_confirm_upload+"<br>"+file.name,
		size: 'small',
		buttons: {
				confirm: {
						label: $lang.bootbox_btn_confirm,
						className: 'btn-success'
				},
				cancel: {
						label: $lang.bootbox_btn_no,
						className: 'btn-danger'
				}
		},
		callback: function (result) {
			if (result) {
				var spanLoader = '<span class="spanloader text-primary" style="position: absolute; top: 45%;left: 45%;z-index: 1000;font-size: 14px"><i class="fa fa-spin fa-5x text-primary fa-spinner"></i><br>Processing.... </span>';
				$("#modal-manage-import .modal-body").append($(spanLoader));
				var formData = new FormData($that[0]);
				$.ajax({
					url: base_url('master-data/ruangan/import_'),
					type: 'POST',
					data: formData,
					async: false,
					cache: false,
					contentType: false,
					enctype: 'multipart/form-data',
					processData: false,
					success: function (response) {
						Msg.success(response.message);
						$tableData.ajax.reload();
						if (response.duplicate && response.duplicate.length>0) {
							$(".row-error .content").html('');
							$(".row-error .title").html('Ruangan sudah terdaftar (Duplicate):');
							var htm = '<ul class="striped-list ml-0 pl-3" > ';
							$.each(response.duplicate, function (i,item) {
								htm+='<li>'+item+'</li>'
							})
							htm += '</ >';
							$('.row-error .content').html(htm);
						} else {
							if (response.link) {
								location.href = response.link;
							}
						}
						
					},
					error: function (err) {
						var json = err.responseJSON;
						Msg.error(json.error);
						if (json.data && json.data.length>0) {
							$(".row-error .content").html('');
							$(".row-error .title").html('Error Data sheet tidak sesuai:');
							var htm = '<ul class="striped-list ml-0 pl-3" > ';
							$.each(json.data, function (i,item) {
								htm+='<li>'+item+'</li>'
							})
							htm += '</ >';
							$('.row-error .content').html(htm);
						}

					},
					complete: function (response) {
						btn_control.removeClass('btn-primary').addClass('btn-warning');
						$("#file").val('');
						$(".filename").text('');
						//loading_hide();
						$(".spanloader").remove();
					}
				})
			} else {
				alert("closed");
				btn_control.removeClass('btn-primary').addClass('btn-warning');
				$("#file").val('');
				$(".filename").text('');
			}
		}
	});
})
		

		$("#kelas").select2({
			minimumResultsForSearch: -1,
			placeholder: "Kelas Ruangan",
            tags: true,
            ajax: { 
            url: base_url('master-data/ruangan/select2_kelas_ruangan'),
			type: "GET",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    key: params.term,
					clinic_id: getSelectedClinic(),
                };
            },
            processResults: function (search) {
                return {
                    results: search
                };
            },
                cache: true
        }
    });

		$("#kategori").select2({
			minimumResultsForSearch: -1,
			placeholder: "Kategori Ruangan",
            tags: true,
            ajax: { 
            url: base_url('master-data/ruangan/select2_kategori_ruangan'),
            type: "GET",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    key: params.term,
					clinic_id: getSelectedClinic(),
                };
            },
            processResults: function (search) {
                return {
                    results: search
                };
            },
                cache: true
        }
    });
