$modalManageJenisPemeriksaan="#modal-manage-jenis-pemeriksaan"
$modalManageDokter="#modal-manage-dokter"
$modalManageJenisSample="#modal-manage-jenis-sample"
$modalManageNotes="#modal-manage-notes"
$tableUsers = $("#tableUsers").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: 25,
    ajax: {
        url: base_url($module_path_lab+'administrative/load-dt-users'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           d.clinic_id=getSelectedClinic()
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    sorting: [[1,'ASC']],
    columnDefs: [
        { targets: [0], width: '35px',className: 'text-center' },
        { targets: [5], width: '79px',className: 'text-center' },
        { targets: [-1], width: '50px',className: 'text-center',searchable: false,orderable: false },
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
        var selected="", set_status="Inactive", btnclass="btn-danger";
        if(parseInt(data[5])==1){
            selected="checked='checked'"; set_status="Active";
            btnclass="btn-success";
        }else{
            $(row).addClass("text-danger");
        }
        $("td:eq(5)",row).html($('<label class="ckbox"><input type="checkbox" class="set_status_user" data-id="'+data[0]+'" name="is_enabled" value="1" '+selected+'> <span>'+set_status+'</span></label>'));
        //$("td:eq(4)",row).html($('<label class="ctc-toggle-active btn-status2"><input class="hide" type="checkbox" '+selected+'><span> '+set_status+'</span></label>'));
    },
})
$tableJenisPemeriksaan = $("#tableJenisPemeriksaan").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: 25,
    ajax: {
        url: base_url($module_path_lab+'administrative/load-jenis-pemeriksaan'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           d.clinic_id=getSelectedClinic()
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    // scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    sorting: [[1,'ASC']],
    columnDefs: [
       {targets: [2,3],width: '100px'}
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        
    },
})
$tableDokter = $("#tableDokter").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "25",
    ajax: {
        url: base_url($module_path_lab+'administrative/load-dokter'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           d.clinic_id=getSelectedClinic()
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    scrollCollapse: true,
    scrollX: false,
    sorting: [[1,'ASC']],
    columnDefs: [
       
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        
    },
})
$tableJenisSample = $("#table-jenis-sample").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "25",
    ajax: {
        url: base_url($module_path_lab+'pemeriksaan/load-dt-jenis-sample'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           d.clinic_id=getSelectedClinic()
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    scrollCollapse: true,
    scrollX: false,
    sorting: [[1,'ASC']],
    columnDefs: [
       
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        
    },
})
$tableNotes = $("#table-notes").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "10",
    ajax: {
        url: base_url($module_path_lab+'pemeriksaan/load-dt-notes'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           d.clinic_id=getSelectedClinic()
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    sorting: [[2,'ASC']],
    columnDefs: [
			{ targets: [2], width: '70px',className: 'text-center' }
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        
    },
})
$modalManageprovider = "#modal-manage-provider";
$tableProvider = $("#dataProvider").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: "25",
    ajax: {
        url: base_url($module_path_lab+'provider/load-dt'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           d.clinic_id=getSelectedClinic()
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    scrollCollapse: true,
    scrollX: false,
    sorting: [[1,'ASC']],
    columnDefs: [
        // { targets: [0], width: '35px',className: 'text-center' },
        // { targets: [4], width: '79px',className: 'text-center' },
        // { targets: [-1], width: '50px',className: 'text-center',searchable: false,orderable: false },
    ],
    rowCallback: function(row, data, iDisplayIndex){
        // var info = this.fnPagingInfo();
        // var page = info.iPage;
        // var length = info.iLength;
        // var index = page * length + (iDisplayIndex + 1);
        // $('td:eq(0)', row).html(index);
        // var selected="", set_status="Inactive", btnclass="btn-danger";
        // if(parseInt(data[4])==1){
        //     selected="checked='checked'"; set_status="Active";
        //     btnclass="btn-success";
        // }else{
        //     $(row).addClass("text-danger");
        // }
        // $("td:eq(4)",row).html($('<label class="ckbox"><input type="checkbox" class="set_status_user" data-id="'+data[0]+'" name="is_enabled" value="1" '+selected+'> <span>'+set_status+'</span></label>'));
        
    },
})
$tableOthersSetting = $("#table-others-setting").DataTable({
    serverSide: true,
		ordering: false,
		searching: false,
	paging: false,
		info: false,
    pageLength: "25",
    ajax: {
        url: base_url($module_path_lab+'administrative/load_others_setting'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           d.clinic_id=getSelectedClinic()
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    scrollCollapse: true,
    scrollX: false,
    sorting: [[1,'ASC']],
    columnDefs: [
       { targets: [0], width: '150px' },
       { targets: [2], width: '70px',className: 'text-center' }
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        
    },
})
$("#search_provider").select2({
	minimumInputLength: 0,
	allowClear: true,
	multiple: false,
	placeholder: 'search',
	ajax: {
		url: base_url($module_path_lab+'provider/select2-'),
		headers: {
			'x-user-agent': 'ctc-webapi'
		},
		data: function(params) {
			return {
				search: params.term
			}
		},
		processResults: function(data){
			return {results: data};
		}
	},
})
$(document)
.ready(function(){
    getActiveLang($module_path_lab+'administrative');
    
})
.on("change", "#source_clinic", function () {
	$tableJenisPemeriksaan.ajax.reload();
	$tableDokter.ajax.reload();
	$tableJenisSample.ajax.reload();
	$tableNotes.ajax.reload();
	$tableProvider.ajax.reload();
	$tableUsers.ajax.reload();
})
.on("click",".add-user",function(){
    $modal_id="#modal-manage-user";
    $modal_body=$($modal_id).find('.modal-body');
    $modal_body.find('input[name="user_id"]').val('');
    
    $modal_body.find("input:text").val("");
    $modal_body.find("[name*='password']").attr('required');
    $modal_body.find(".show-on-update").addClass('hide');
    $($modal_id).modal({
        effect: 'effect-slide-in-right',
        backdrop: 'static',
        keyboard: false,
        show: true
    })
})
.on("click",".set_status_user",function(){
    var $that=$(this);
    var status=0;
    var endisUser=" Disabled this user";
    var id=$that.data('id');
    if($that.is(":checked")){
           status=1;  
           var endisUser=" Enabled this user";
    }
    bootbox.confirm({
        title: $lang.bootbox_title_confirmation,
        message: $lang.bootbox_message_confirm_+endisUser+"?",
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
                http_request($module_path_lab+'administrative/enable-disable-user/','POST',{id: id,status: status})
                .done(function(res){
                    Msg.success(res.message);
                    $tableUsers.ajax.reload(null,false);
                })
                .fail(function(err){
                    if($that.is(":checked")){
                        $that.prop("checked",false);
                    }else{
                        $that.prop("checked",true);
                    }
                })
            }else{
                if($that.is(":checked")){
                    $that.prop("checked",false);
                }else{
                    $that.prop("checked",true);
                }
            }
        }
    });
})
.on("keypress",".no-space",function(e){
    if(e.keyCode==32){
        Msg.error($lang.msg_space_no_allowed);
        return false;  
    } 
})
.on("click",".link-edit-user",function(){
    http_request($module_path_lab+'administrative/search-user','GET',{id: $(this).data('id')})
    .done(function(result){
        var data=result.data;
        $modal_id="#modal-manage-user";
        $modal_body=$($modal_id).find('.modal-body');
        $.each(data,function(key,val){
            $modal_body.find("[name='"+key+"']").val(val);
		})
		if (data.provider_id != "") {
			$("#search_provider").append('<option value="'+data.provider_id+'" selected="selected">'+data.nama_provider+'</option>')
		}
        $modal_body.find("[name*='password']").removeAttr('required');
        $modal_body.find(".show-on-update").removeClass('hide');
        $(".accessibility").prop("checked",false);
        if(result.privilege && result.privilege.superAdmin==false){
            $.each(result.privilege.accessibility,function(i,dt){
                $("[name^='accessibility'][value='"+dt+"']").prop("checked",true);
            })
            $.each(result.privilege.actions_code,function(i,dt){
                $("[name^='actions_code'][value='"+dt+"']").prop("checked",true);
            })
        }else
        if(result.privilege && result.privilege.superAdmin==true){
            $(".accessibility").prop("checked",true);
        }
        $($modal_id).modal({
            effect: 'effect-slide-in-right',
            backdrop: 'static',
            keyboard: false,
            show: true
        })
        //showRemoveIconOnProcess();
    })
})
.on("submit","form[name='form-manage-user']",function(e){
    e.preventDefault();
    $form=$(this).closest('form');
    var data=$form.serialize();
    http_request($module_path_lab+'administrative/save-user','POST',data)
    .done(function(res){
        Msg.success(res.message);
        $("#modal-manage-user").modal('hide');
        $tableUsers.ajax.reload();
    })
})
	.on("click", ".add-provider", function () {
		$modal_id=$modalManageprovider;
    $modal_body=$($modal_id).find('.modal-body');
    // $modal_body.find('input[name="user_id"]').val('');
    
    $modal_body.find("input:text").val("");
    $modal_body.find("textarea[name='alamat']").val("");
    // $modal_body.find("[name*='password']").attr('required');
    $modal_body.find(".show-on-update").addClass('hide');
    $($modal_id).modal({
        effect: 'effect-slide-in-right',
        backdrop: 'static',
        keyboard: false,
        show: true
    })
	})
	.on("click",".link-edit-provider",function(){
		http_request($module_path_lab+'provider/search__','GET',{id: $(this).data('id')})
		.done(function(result){
			var data=result.data;
			$modal_id=$modalManageprovider;
			$modal_body = $($modal_id).find('.modal-body');
			$.each(data, function (key, val) {
				$modal_body.find("[name='"+key+"']").val(val);
			})
			$($modal_id).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
			})
		})
	})
	.on("submit","form[name='form-manage-provider']",function(e){
		e.preventDefault();
		$form=$(this).closest('form');
		var data=$form.serialize();
		http_request($module_path_lab+'provider/save__','POST',data)
		.done(function(res){
			Msg.success(res.message);
			$($modalManageprovider).modal('hide');
			$tableProvider.ajax.reload();
		})
	})
	.on("click",".link-delete-provider",function(){
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
					http_request($module_path_lab+'provider/delete__/'+id,'DELETE',{})
					.done(function(res){
						Msg.success(res.message);
						$tableProvider.ajax.reload(null,false);
					})
				}
			}
		});
	})

	// jenis pemeriksaan setting
	.on("click", "[name^='group_hasil1'],[name='group_hasil2']", function () {
		var grp = $(this).data('group');

		$("[name*='group_hasil']").each(function () {
			if ($(this).data('group') != grp) {
				$(this).prop('checked',false)
			} else {
				$(this).prop('checked', true);
				var nm = $(this).attr('name');
				console.log(nm, $(this).val())
				$("input[data-groupv='"+nm+"']").val($(this).val())
			}
		})
	})
	// .on("click", "[name='group_hasil2']", function () {
	// 	// Msg.error("Silahkan pilih nilai di hasil 1. hasil 2 akan mengikuti")
	// 	var grp = $(this).data('group');
	// 	$("[name*='group_hasil']").each(function () {
	// 		if ($(this).data('group') != grp) {
	// 			$(this).prop('checked',false)
	// 		} else {
	// 			$(this).prop('checked', true);
	// 			var nm = $(this).attr('name');
	// 			console.log(nm, $(this).val())
	// 			$("input[data-groupv='"+nm+"']").val($(this).val())
	// 		}
	// 	})	
	// 	// return false;
	// })
	.on("keyup", "[name='pemeriksaan1[]']", function () {
		var ind = $(this).index();
		$("[name='pemeriksaan2["+ind+"]").val($(this).val())
	})
.on("click",".add-jenis-pemeriksaan",function(){
	$modal_id=$modalManageJenisPemeriksaan;
	$modal_body=$($modal_id).find('.modal-body');
	$modal_body.find("input:text").val("");
	$modal_body.find("textarea[name='alamat']").val("");
	$modal_body.find(".show-on-update").addClass('hide');
	$($modal_id).modal({
		effect: 'effect-slide-in-right',
		backdrop: 'static',
		keyboard: false,
		show: true
	})
})

.on("click",".link-delete-jenis-pemeriksaan",function(){
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
				http_request($module_path_lab+'jenispemeriksaan/delete__/'+id,'DELETE',{})
				.done(function(res){
					Msg.success(res.message);
					$tableJenisPemeriksaan.ajax.reload(null,false);
				})
			}
		}
	});
})
	.on("click", ".add-row-opsi-pemeriksaan", function () {
	// 	var getLength = $("[name^='nama_pemeriksaan']").length;
	// var row = '<tr>' +
	// 	'<td>' +
	// 	'<input type="text" class="form-control input-sm" name="nama_pemeriksaan[]" placeholder="Nama Pemeriksaan">' +
	// 	'</td>' +
	// 	'<td>' +
	// 	'<input type="text" class="form-control input-sm" name="hasil[]" placeholder="Hasil">' +
	// 	'</td>' +
	// 	'<td>' +
	// 	'<input type="text" class="form-control input-sm" name="nilai_rujukan[]" placeholder="Nilai Rujukan">' +
	// 	'</td>' +
	// 	'</tr>';
		var row_table1='<tr class="row-append">'+
											'<td class="p-1">'+
												'<input type="hidden" class="form-control input-sm" name="is_main[]" value="0">'+
												'<input type="hidden" class="form-control input-sm" name="hasil_id1[]" value="">'+
												'<input type="text" class="form-control input-sm" name="pemeriksaan[]">'+
											'</td>'+
											'<td class="p-1"><input type="text" class="form-control input-sm" name="hasil1[]"></td>'+
											'<td class="p-1"><input type="text" class="form-control input-sm" name="nilai_rujukan1[]"></td>'+
										'</tr>';
		$(".table-hasil1 tbody").append($(row_table1));
			var row_table2='<tr class="row-append">'+
			'<td class="p-1">'+
			'<input type = "hidden" class="form-control input-sm" name = "hasil_id2[]" value="">' +
			'<input type = "text" class="form-control input-sm" name = "hasil2[]" >' +
			'</td > '+
											'<td class="p-1"><input type="text" class="form-control input-sm" name="nilai_rujukan2[]"></td>'+
											'<td class="p-1 tx-center"><i class="fa fa-times text-danger pointer remove-row"></i></td>'+
										'</tr>';
	$(".table-hasil2 tbody").append($(row_table2));
})
.on("click", ".link-edit-jenis-pemeriksaan", function () {
	http_request($module_path_lab+'jenispemeriksaan/search__', 'GET', { id: $(this).data('id') })
	.done(function(result){
		var data = result.data;
		var jns_periksa=data[0]
		$("table tr.row-append").remove();
		$modal_id=$modalManageJenisPemeriksaan;
		$modal_body = $($modal_id).find('.modal-body');
		$("[name='kategori'][value='"+jns_periksa.category+"']").trigger("click")
		if (jns_periksa.category == 'covid') {
			var opsi_hasil = result.list_hasil;
			if (opsi_hasil.length > 0) {
				var search_main = _.filter(opsi_hasil, function (item) {
					return item.is_main == 1;
				})
				var search_nonmain = _.filter(opsi_hasil, function (item) {
					return item.is_main == 0;
				})
				if (search_main.length > 0) {
					$("[name='group_hasil1'][value='" + search_main[0].group_hasil + "']").removeAttr('readonly').trigger('click');
					$("[name='group_hasil1'][value!='" + search_main[0].group_hasil + "']").attr("readonly", "readonly")
					$("[name='hasil_id1[]").val(search_main[0].id);
					$("[name='hasil_id2[]").val(search_main[1].id);
					$("[name='pemeriksaan[]").val(search_main[0].nama_pemeriksaan);
				}
				if (search_nonmain.length > 0) {
					var search_another = _.filter(search_nonmain, function (s) {
						return s.group_hasil != search_main[0].group_hasil
					})
					$.each(_.filter(search_nonmain, function (s) {
						return s.group_hasil == search_main[0].group_hasil
					}), function (i, item) {
						var row_table1 = '<tr class="row-append">' +
							'<td class="p-1">' +
							'<input type="hidden" class="form-control input-sm" name="is_main[]" value="0">' +
							'<input type="hidden" class="form-control input-sm" name="hasil_id1[]" value="' + item.id + '">' +
							'<input type="text" class="form-control input-sm" name="pemeriksaan[]" value="' + item.nama_pemeriksaan + '">' +
							'</td>' +
							'<td class="p-1"><input type="text" class="form-control input-sm" name="hasil1[]" value="' + item.hasil + '"></td>' +
							'<td class="p-1"><input type="text" class="form-control input-sm" name="nilai_rujukan1[]" value="' + item.nilai_rujukan + '"></td>' +
							'</tr>';
						$(".table-hasil1 tbody").append($(row_table1));
							
						var row_table2 = '<tr class="row-append">' +
							'<td class="p-1">' +
							'<input type="hidden" class="form-control input-sm" name="hasil_id2[]" value="' + search_another[i].id + '">' +
							'<input type="text" class="form-control input-sm" name="hasil2[]" value="' + search_another[i].hasil + '">' +
							'</td > ' +
							'<td class="p-1"><input type="text" class="form-control input-sm" name="nilai_rujukan2[]" value="' + search_another[i].nilai_rujukan + '"></td>' +
							'<td class="p-1 tx-center"><i class="fa fa-times text-danger pointer remove-row while-edit" data-ids="' + item.id + ',' + search_another[i].id + '"></i></td>' +
							'</tr>';
						$(".table-hasil2 tbody").append($(row_table2));
					})
				}
			}
		} else { // and covid
			// process result.detail
			var main_el = $("#modal-manage-jenis-pemeriksaan .list_hasil .append-row-pemeriksaan div.row-item.main");
			if (main_el.length > 0) main_el.remove();
			var sub_el = $("#modal-manage-jenis-pemeriksaan .list_hasil .append-row-pemeriksaan div.row-sub-item");
			if (sub_el.length > 0) sub_el.remove();
			console.log(result.detail)
			var parent_index = 1;
			$.each(result.detail, function (i, dt) {
				var htm = createMainRowJenis(dt);
				$("#modal-manage-jenis-pemeriksaan .list_hasil .append-row-pemeriksaan").append($(htm))
				if (dt.sub) {
					$.each(dt.sub, function (x, dt_sub) {
						var sub = createSubRowJenis(parent_index, dt_sub)
						var target_elem = $("#modal-manage-jenis-pemeriksaan .list_hasil .append-row-pemeriksaan .row-sub-item[data-index='" + parent_index + "']:last")
						if (target_elem.length > 0) {
							$(sub).insertAfter(target_elem);
						} else {
							$(sub).insertAfter($("#modal-manage-jenis-pemeriksaan .list_hasil .append-row-pemeriksaan .row-item[data-index='" + parent_index + "']:last"));
						}
					})
				}
				parent_index++
			})
			renewNumberingJenis();
			
		}
		$("[name='jenis_pemeriksaan']").val(jns_periksa.jenis);
		$("[name='metode']").val(jns_periksa.metode);
		$("[name='_id']").val(jns_periksa._id);
		$($modal_id).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
		})
	})
})
	.on("click", "[name='group_hasil1'][readonly]", function () {
		// Msg.error("Hasil 1 & 2 tidak bisa di ubah by edit. data yang dapat diubah hanya list pemeriksaan, silakan hapus dan buat baru")
		// return false;
	})
	.on("click", ".remove-row", function () {
		if ($(this).is(".while-edit")) {
			var that=$(this)
			var ids=$(this).data("ids")
			bootbox.confirm({
				title: $lang.bootbox_title_confirmation,
				message: "Baris pemeriksaan ini akan dihapus tanpa perlu di submit, Yakin?",
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
						http_request($module_path_lab+'jenispemeriksaan/delete_opsi_hasil__/','DELETE',{ids: ids})
						.done(function(res){
							Msg.success(res.message);
							$tableJenisPemeriksaan.ajax.reload(null, false);
							var ind = that.closest('tr').index()
							that.closest('tr').remove();
							$(".table-hasil1 tr:eq("+ind+")").remove();
						})
					}
				}
			});
		} else {
				var ind = $(this).closest('tr').index()
			$(this).closest('tr').remove();
			$(".table-hasil1 tr:eq("+ind+")").remove();
		}
		
	})
.on("submit","form[name='form-manage-jenis-pemeriksaan']",function(e){
	e.preventDefault();
	$form=$(this).closest('form');
	var data=$form.serialize();
	http_request($module_path_lab+'jenispemeriksaan/save__','POST',data)
	.done(function(res){
		Msg.success(res.message);
		$($modalManageJenisPemeriksaan).modal('hide');
		$tableJenisPemeriksaan.ajax.reload();
	})
})
.on("click",".add-dokter",function(){
	$modal_id=$modalManageDokter;
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
.on("click", ".link-edit-dokter", function () {
	http_request($module_path_lab+'dokter/search__', 'GET', { id: $(this).data('id') })
	.done(function(result){
		var data=result.data;
		$modal_id=$modalManageDokter;
		$modal_body = $($modal_id).find('.modal-body');
		$.each(data, function (key, val) {
			$modal_body.find("[name='"+key+"']").val(val);
		})
		$($modal_id).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
		})
	})
})
.on("click",".link-delete-dokter",function(){
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
				http_request($module_path_lab+'dokter/delete__/'+id,'DELETE',{})
				.done(function(res){
					Msg.success(res.message);
					$tableDokter.ajax.reload(null,false);
				})
			}
		}
	});
})
.on("submit","form[name='form-manage-dokter']",function(e){
	e.preventDefault();
	$form=$(this).closest('form');
	var data=$form.serialize();
	http_request($module_path_lab+'dokter/save__','POST',data)
	.done(function(res){
		Msg.success(res.message);
		$($modalManageDokter).modal('hide');
		$tableDokter.ajax.reload();
	})
})
.on("click",".add-jenis-sample",function(){
	$modal_id=$modalManageJenisSample;
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
.on("click", ".link-edit-jenis-sample", function () {
	http_request($module_path_lab+'pemeriksaan/search-jenis-sample__', 'GET', { id: $(this).data('id') })
	.done(function(result){
		var data=result.data;
		$modal_id=$modalManageJenisSample;
		$modal_body = $($modal_id).find('.modal-body');
		$.each(data, function (key, val) {
			$modal_body.find("[name='"+key+"']").val(val);
		})
		$($modal_id).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
		})
	})
})
.on("click",".link-delete-jenis-sample",function(){
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
				http_request($module_path_lab+'pemeriksaan/delete-jenis-sample__/'+id,'DELETE',{})
				.done(function(res){
					Msg.success(res.message);
					$tableJenisSample.ajax.reload(null,false);
				})
			}
		}
	});
})
.on("submit","form[name='form-manage-jenis-sample']",function(e){
	e.preventDefault();
	$form=$(this).closest('form');
	var data=$form.serialize();
	http_request($module_path_lab+'pemeriksaan/save-jenis-sample__','POST',data)
	.done(function(res){
		Msg.success(res.message);
		$($modalManageJenisSample).modal('hide');
		$tableJenisSample.ajax.reload();
	})
})
.on("click",".add-notes",function(){
	$modal_id=$modalManageNotes;
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
.on("click", ".link-edit-notes", function () {
	http_request($module_path_lab+'pemeriksaan/search-notes__', 'GET', { id: $(this).data('id') })
	.done(function(result){
		var data=result.data;
		$modal_id=$modalManageNotes;
		$modal_body = $($modal_id).find('.modal-body');
		$.each(data, function (key, val) {
			if(key=='id') key='_id'
			$modal_body.find("[name='"+key+"']").val(val);
		})
		$($modal_id).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
		})
	})
})
.on("click",".link-delete-notes",function(){
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
				http_request($module_path_lab+'pemeriksaan/delete-notes__/'+id,'DELETE',{})
				.done(function(res){
					Msg.success(res.message);
					$tableNotes.ajax.reload(null,false);
				})
			}
		}
	});
})
.on("submit","form[name='form-manage-notes']",function(e){
	e.preventDefault();
	$form=$(this).closest('form');
	var data=$form.serialize();
	http_request($module_path_lab+'pemeriksaan/save-notes__','POST',data)
	.done(function(res){
		Msg.success(res.message);
		$($modalManageNotes).modal('hide');
		$tableNotes.ajax.reload();
	})
})
	.on("click", ".link-edit-others-setting", function () {
		var id = $(this).data('id');
		http_request($module_path_lab+'administrative/get-setting/' + id, 'GET', {})
	.done(function(result){
		var data = (result.length > 0) ? result[0] : {};
		$modal_id="#modal-manage-others-setting";
		$modal_body = $($modal_id).find('.modal-body');
		$.each(data, function (key, val) {
			$modal_body.find("[name='"+key+"']").val(val);
		})
		$($modal_id).modal({
				effect: 'effect-slide-in-right',
				backdrop: 'static',
				keyboard: false,
				show: true
		})
	})
})
.on("submit","form[name='form-manage-others-setting']",function(e){
	e.preventDefault();
	$form=$(this).closest('form');
	var data=$form.serialize();
	http_request($module_path_lab+'administrative/save-others-setting__','POST',data)
	.done(function(res){
		Msg.success(res.message);
		$("#modal-manage-others-setting").modal('hide');
		$tableOthersSetting.ajax.reload();
	})
})
.on("click",".btn-status",function(){
    $that=$(this);
    if($that.find("input:checkbox").is(":checked")){
        $that.removeClass('btn-success').addClass('btn-danger');
        $that.find('span').text('In active');
    }else{
        $that.removeClass('btn-danger').addClass('btn-success');
        $that.find('span').text('Active');
    }
})
.on("click",".accessibility",function(){
    $this=$(this);
    var val=$this.val();
    if($this.is(":checked") && val=='c-spadmin'){
        $("input[type='checkbox'].accessibility").prop("checked",true);
    }else
    if($this.is(":checked")==false && val!='c-spadmin'){
        $this.closest('li').find("ul input.accessibility[name^='actions_code']").prop("checked",false);
    }
})
.on("click",".accessibility[name^='actions_code']",function(){
    $this=$(this);
    $parent_ul=$this.closest('ul');
    $parent_li=$parent_ul.closest('li');
    var val=$this.val();
    var any=false;
    if($this.is(":checked")){
        $parent_li.find(".accessibility[name^='accessibility']").prop('checked',true);
    }else{
        $parent_ul.find(".accessibility[name^='actions_code']").each(function(){
            if($(this).is(":checked")){
                any=true;
            }
        })
        setTimeout(function(){
            if(!any)  $parent_li.find(".accessibility[name^='accessibility']").prop('checked',false);
           },100)
    }
})
.on("click", "#modal-manage-jenis-pemeriksaan [name='kategori']", function () {
	var val = $(this).val()
	if (val == 'umum') {
		$(".show-kategori-covid").addClass('hide');
		$(".show-kategori-umum").removeClass('hide');
	} else {
		$(".show-kategori-umum").addClass('hide');
		$(".show-kategori-covid").removeClass('hide');
	}
})
.on("click", "#modal-manage-jenis-pemeriksaan .add-row-item-pemeriksaan", function () {
	var htm = createMainRowJenis()
	$("#modal-manage-jenis-pemeriksaan .list_hasil .append-row-pemeriksaan").append($(htm))
	renewNumberingJenis();
})
.on("click", "#modal-manage-jenis-pemeriksaan .add-sub-item", function () {
	var parent_index = $(this).closest('div[data-index]').data("index");
	var htm = createSubRowJenis(parent_index)
	var target_elem = $("#modal-manage-jenis-pemeriksaan .list_hasil .append-row-pemeriksaan .row-sub-item[data-index='" + parent_index + "']:last")
	if (target_elem.length > 0) {
		$(htm).insertAfter(target_elem);
	} else {
		$(htm).insertAfter($("#modal-manage-jenis-pemeriksaan .list_hasil .append-row-pemeriksaan .row-item[data-index='"+parent_index+"']:last"));
	}
})
.on("click", "#modal-manage-jenis-pemeriksaan .delete-sub-item", function () {
	$(this).closest('.row-sub-item').remove();
	})
var showRemoveIconOnProcess=function(){
    $(".row-internal-process-unselected label.ckbox,.row-external-process-unselected label.ckbox").hover(function(){
        $(this).find('.remove-process').removeClass('hide');
    },function(){
        $(this).find('.remove-process').addClass('hide');
    })
}

function createMainRowJenis (data = {}) {
	// search last index
	var last = null;
	var m = $('#modal-manage-jenis-pemeriksaan .row-item.main')
	if (m.length == 0) last = 0;
	$('#modal-manage-jenis-pemeriksaan .row-item.main').each(function () {
		var index = parseFloat($(this).data('index'));
		last = (index > last) ? index : last;
	});
	var value = {
		name: data.name || data.item || '',
		rujukan: data.rujukan || data.nilai_rujukan || '',
		satuan: data.satuan || '',
	}
	var newIndex= last + 1;
	var htm='<div class="row row-item main mb-1" data-index="'+newIndex+'">'+
						'<div class="col-1 border">'+
							'<b class="no"></b>'+
						'</div>'+
						'<div class="col-6">'+
							'<input type="text" name="item_periksa_umum['+newIndex+'][name]" value="'+value.name+'" class="form-control input-sm" placeholder="Item Periksa">'+
'									<button type="button" class="btn btn-xs add-sub-item p-0" data-toggle="tooltip" title="Tambah Sub Item Pemeriksaan" index="4"><i class="fa fa-plus-circle tx-success"></i></button>'+
						'</div>'+
						'<div class="col-3">'+
							'<input type="text" name="item_periksa_umum['+newIndex+'][rujukan]" value="'+value.rujukan+'" class="form-control input-sm" placeholder="Nilai Rujukan">'+
						'</div>' +
						'<div class="col-2">' +
							'<input type="text" name="item_periksa_umum['+newIndex+'][satuan]" value="'+value.satuan+'" class="form-control input-sm" placeholder="Satuan Nilai">' +
						'</div>' +
					'</div>';
	return htm;
}
function createSubRowJenis (index, data = {}) {
	var k = Math.floor(Math.random() * 1000000000);
	var value = {
		name: data.name || data.item || '',
		rujukan: data.rujukan || data.nilai_rujukan || '',
		satuan: data.satuan || ''
	}
	var htm='<div class="row row-sub-item sub mb-1" data-index="'+index+'">'+
			'<div class="col-2 border tx-right pr-1">'+
				'<div class="btn-group-sm">'+
					'<button type="button" class="btn btn-xs delete-sub-item"><i class="fa fa-minus-circle tx-danger"></i></button>'+
				'</div>'+
			'</div>'+
			'<div class="col-5 pl-1">'+
				'<input type="text" name="item_periksa_umum['+index+'][sub]['+k+'][name]" value="'+value.name+'" class="form-control input-sm" placeholder="Sub Item Periksa">'+
			'</div>'+
			'<div class="col-3">'+
				'<input type="text" name="item_periksa_umum['+index+'][sub]['+k+'][rujukan]" value="'+value.rujukan+'" class="form-control input-sm" placeholder="Nilai Rujukan">'+
			'</div>'+
			'<div class="col-2">'+
				'<input type="text" name="item_periksa_umum['+index+'][sub]['+k+'][satuan]" value="'+value.satuan+'" class="form-control input-sm" placeholder="Satuan Nilai">'+
			'</div>'+
		'</div>';
	return htm;
}
function renewNumberingJenis () {
	var no=1
	$("#modal-manage-jenis-pemeriksaan .row-item.main").each(function () {
		$(this).find('b.no').html(no)
		no++;
	})
}
