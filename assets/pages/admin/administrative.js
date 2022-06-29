$modalManageJenisPemeriksaan="#modal-manage-jenis-pemeriksaan"
$modalManageDokter="#modal-manage-dokter"
$modalManageJenisSample="#modal-manage-jenis-sample"
$modalManageNotes = "#modal-manage-notes"

$tableUsers = $("#tableUsers").DataTable({
    serverSide: true,
    ordering: true,
    pageLength: 25,
    ajax: {
        url: base_url('admin/administrative/load-dt-users'),
        type: 'POST',
        headers: {
            'x-user-agent': 'ctc-webapi',
        },
        data: function(d) {
           d.clinic_id = getSelectedClinic();
        }
    },
    language: DataTableLanguage(),
    responsive: true,
    scrollY: '50vh',
    // scrollCollapse: true,
    scrollX: false,
    sorting: [[1,'ASC']],
	columnDefs: [
		{targets: [1],visible: (getSelectedClinic()==='default') ? false : true},
        { targets: [0], width: '35px',className: 'text-center' },
        { targets: [7], width: '45px',className: 'text-center' },
        // { targets: [4], width: '79px',className: 'text-center' },
        // { targets: [-1], width: '50px',className: 'text-center',searchable: false,orderable: false },
    ],
    rowCallback: function(row, data, iDisplayIndex){
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
		var selected = "", set_status = "Inactive", btnclass = "btn-danger";
		var col_status=(getSelectedClinic()==='default') ? 4 : 5
        if(parseInt(data[5])==1){
            selected="checked='checked'"; set_status="Active";
            btnclass="btn-success";
        }else{
            $(row).addClass("text-danger");
        }
        $("td:eq("+col_status+")",row).html($('<label class="ckbox"><input type="checkbox" class="set_status_user" data-id="'+data[0]+'" name="is_enabled" value="1" '+selected+'> <span>'+set_status+'</span></label>'));
        //$("td:eq(4)",row).html($('<label class="ctc-toggle-active btn-status2"><input class="hide" type="checkbox" '+selected+'><span> '+set_status+'</span></label>'));
    },
})

$(document)
.ready(function(){
    getActiveLang('admin/administrative');
    
})
	.on("change", "#source_clinic", function () {
		$tableUsers.ajax.reload()
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
                http_request('admin/administrative/enable-disable-user/','POST',{id: id,status: status})
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
    http_request('admin/administrative/search-user','GET',{id: $(this).data('id')})
    .done(function(result){
			var data=result.data;
			$modal_id="#modal-manage-user";
			$modal_body=$($modal_id).find('.modal-body');
			$.each(data,function(key,val){
				$modal_body.find("[name='"+key+"']").val(val);
			})

			$modal_body.find("[name*='password']").removeAttr('required');
			$modal_body.find(".show-on-update").removeClass('hide');
			$(".accessibility").prop("checked",false);
			if (result.privilege && result.privilege.superAdmin == false) {
					$.each(result.privilege.accessibility_base,function(i,dt){
							$("[name^='accessibility_base'][value='"+dt+"']").prop("checked",true);
					})
					$.each(result.privilege.actions_code_base,function(i,dt){
							$("[name^='actions_code_base'][value='"+dt+"']").prop("checked",true);
					})
					$.each(result.privilege.accessibility,function(i,dt){
							$("[name^='accessibility_menu'][value='"+dt+"']").prop("checked",true);
					})
					$.each(result.privilege.actions_code,function(i,dt){
							$("[name^='actions_code_menu'][value='"+dt+"']").prop("checked",true);
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
    var data=$form.serialize()+'&clinic_id='+getSelectedClinic();
    http_request('admin/administrative/save-user','POST',data)
    .done(function(res){
        Msg.success(res.message);
        $("#modal-manage-user").modal('hide');
        $tableUsers.ajax.reload();
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
	$this = $(this);
	var val=$this.val();
	if ($this.is(":checked") && val == 'c-spadmin') {
		$("input[type='checkbox'].accessibility").prop("checked", true);
	} else{
			if ($this.attr('name').indexOf('accessibility_base') > -1) {
				var checked = ($this.is(":checked")) ? true : false
				$this.closest('li').find("ul li input.accessibility").prop("checked", checked);
			} else
				if (val != 'c-spadmin') {
					$this.closest('li').find("ul input.accessibility[name^='actions_code']").prop("checked", false);
			}
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
var showRemoveIconOnProcess=function(){
    $(".row-internal-process-unselected label.ckbox,.row-external-process-unselected label.ckbox").hover(function(){
        $(this).find('.remove-process').removeClass('hide');
    },function(){
        $(this).find('.remove-process').addClass('hide');
    })
}
